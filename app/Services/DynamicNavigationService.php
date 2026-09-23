<?php

namespace App\Services;

use App\Models\SystemModule;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Illuminate\Support\Facades\Schema;

class DynamicNavigationService
{
    public static function build(NavigationBuilder $builder): NavigationBuilder
    {
        if (! Schema::hasTable('system_modules')) {
            return $builder;
        }

        $allModules = SystemModule::query()
            ->where('status', 'active')
            ->where('show_in_menu', true)
            ->orderBy('order')
            ->get();

        if ($allModules->isEmpty()) {
            SystemModule::seedDefaults();
            $allModules = SystemModule::query()
                ->where('status', 'active')
                ->where('show_in_menu', true)
                ->orderBy('order')
                ->get();
        }

        // Determine permissions of current authenticated user
        $user = auth()->user();
        $isSuperAdmin = $user ? $user->isSuperAdmin() : false;
        $allowedIds = $isSuperAdmin ? null : ($user ? $user->getAllowedSystemModuleIds() : collect());

        // Top level modules
        $topLevelModules = $allModules->whereNull('parent_id')->sortBy('order');

        foreach ($topLevelModules as $topModule) {
            // Find children that belong directly under this parent
            $children = $allModules
                ->where('parent_id', $topModule->id)
                ->whereNull('sub_parent_id')
                ->sortBy('order');

            if ($children->isNotEmpty() || blank($topModule->route)) {
                // Render as a Navigation Group
                $groupItems = [];

                foreach ($children as $child) {
                    // Check for sub-children (Level 3 items)
                    $subChildren = $allModules
                        ->where('sub_parent_id', $child->id)
                        ->sortBy('order');

                    $filteredSubChildren = $subChildren->filter(function ($subChild) use ($isSuperAdmin, $allowedIds) {
                        return $isSuperAdmin || $allowedIds->contains($subChild->id);
                    });

                    // Check if child itself or any of its sub-children is allowed
                    $isChildAllowed = $isSuperAdmin
                        || $allowedIds->contains($child->id)
                        || $filteredSubChildren->isNotEmpty();

                    if (! $isChildAllowed) {
                        continue;
                    }

                    $childItem = NavigationItem::make($child->name)
                        ->icon($child->icon)
                        ->sort($child->order)
                        ->isActiveWhen(fn () => $child->isActive());

                    $resolvedUrl = $child->getResolvedUrl();
                    if ($resolvedUrl) {
                        $childItem->url($resolvedUrl);
                    }

                    if ($filteredSubChildren->isNotEmpty()) {
                        $subChildItems = [];
                        foreach ($filteredSubChildren as $subChild) {
                            $subItem = NavigationItem::make($subChild->name)
                                ->icon($subChild->icon)
                                ->sort($subChild->order)
                                ->isActiveWhen(fn () => $subChild->isActive());

                            $subResolvedUrl = $subChild->getResolvedUrl();
                            if ($subResolvedUrl) {
                                $subItem->url($subResolvedUrl);
                            }

                            $subChildItems[] = $subItem;
                        }

                        $childItem->childItems($subChildItems);
                    }

                    $groupItems[] = $childItem;
                }

                // Only render group if it contains visible items
                if (! empty($groupItems)) {
                    $group = NavigationGroup::make($topModule->name)
                        ->icon($topModule->icon)
                        ->items($groupItems);

                    $builder->group($group);
                }
            } else {
                // Top-level standalone item (e.g. Dashboard)
                $isAllowed = $isSuperAdmin
                    || $topModule->code === 'dashboard'
                    || $allowedIds->contains($topModule->id);

                if (! $isAllowed) {
                    continue;
                }

                $item = NavigationItem::make($topModule->name)
                    ->icon($topModule->icon)
                    ->sort($topModule->order)
                    ->isActiveWhen(fn () => $topModule->isActive());

                $resolvedUrl = $topModule->getResolvedUrl();
                if ($resolvedUrl) {
                    $item->url($resolvedUrl);
                }

                $builder->item($item);
            }
        }

        return $builder;
    }
}
