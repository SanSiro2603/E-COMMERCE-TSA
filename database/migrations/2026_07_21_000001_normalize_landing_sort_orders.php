<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->resequence('home_slides');
        $this->resequence('home_catalog_cards');
        $this->resequence('landing_page_items', ['page', 'section']);
        $this->resequence('landing_catalog_categories');
        $this->resequence('landing_catalog_families', ['category_id']);
        $this->resequence('landing_catalog_animals');
        $this->resequence('landing_catalog_animal_images', ['animal_id']);
    }

    public function down(): void
    {
        // One-based ordering remains valid when this migration is rolled back.
    }

    private function resequence(string $table, array $groupColumns = []): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        if ($groupColumns === []) {
            $this->resequenceQuery($table, []);

            return;
        }

        $groups = DB::table($table)->select($groupColumns)->distinct()->get();

        foreach ($groups as $group) {
            $conditions = [];
            foreach ($groupColumns as $column) {
                $conditions[$column] = $group->{$column};
            }

            $this->resequenceQuery($table, $conditions);
        }
    }

    private function resequenceQuery(string $table, array $conditions): void
    {
        $query = DB::table($table);
        foreach ($conditions as $column => $value) {
            $query->where($column, $value);
        }

        $ids = $query->orderBy('sort_order')->orderBy('id')->pluck('id');

        foreach ($ids as $index => $id) {
            DB::table($table)->where('id', $id)->update(['sort_order' => $index + 1]);
        }
    }
};
