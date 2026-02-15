<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model {
    /** @use HasFactory<\Database\Factories\QuestionFactory> */
    use HasFactory;
    protected $fillable  = [
        'category_id',
        'question',
        'answer',
        'keywords',
    ];
    public function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }
    public function keywordsToArray(): array {
        if (! $this->keywords) {
            return [];
        }
        return array_map(
            fn($keyword) => trim($keyword),
            explode(',', $this->keywords)
        );
    }
    public function hasKeyword(string $keyword): bool {
        return in_array($keyword, $this->keywordsToArray());
    }
}
