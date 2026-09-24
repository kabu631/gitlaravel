<?php

use App\Models\SystemModule;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->boolean('is_active')->default(true);
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('job_openings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('department')->nullable();
            $table->string('location')->default('Kathmandu, Nepal');
            $table->string('type')->default('Full-time');
            $table->text('summary')->nullable();
            $table->longText('description')->nullable();
            $table->string('apply_email')->nullable();
            $table->date('closes_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $path   = config('filament.path', 'secure-admin');
        $parent = SystemModule::where('code', 'content')->first();
        if ($parent) {
            foreach ([
                ['Newsletter Subscribers', 'newsletter_subscribers', 'newsletter-subscribers', 10, 'heroicon-o-envelope'],
                ['Job Openings', 'job_openings', 'job-openings', 11, 'heroicon-o-briefcase'],
            ] as [$name, $code, $slug, $order, $icon]) {
                SystemModule::firstOrCreate(['code' => $code], [
                    'name' => $name, 'route' => "/{$path}/{$slug}", 'order' => $order, 'icon' => $icon,
                    'status' => 'active', 'show_in_menu' => true, 'parent_id' => $parent->id,
                ]);
            }
        }
    }

    public function down(): void
    {
        SystemModule::whereIn('code', ['newsletter_subscribers', 'job_openings'])->delete();
        Schema::dropIfExists('job_openings');
        Schema::dropIfExists('newsletter_subscribers');
    }
};
