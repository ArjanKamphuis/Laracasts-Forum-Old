<?php

namespace App\Filters;

use App\User;

class ThreadFilters extends Filters {
    protected $filters = ['by', 'popular', 'unanswered'];
    
    /**
     * Filter the query by a given username
     * 
     * @param  string $username
     * @return Illuminate\Database\Eloquent\Builder
     */
    protected function by($username) {
        $user = User::where('name', $username)->firstOrFail();
        return $this->builder->where('user_id', $user->id);
    }

    /**
     * Filter the query by most popular threads
     * 
     * @return Illuminate\Database\Eloquent\Builder
     */
    protected function popular() {
        return $this->builder->orderBy('replies_count', 'desc');
    }
    
    /**
     * Filter the query by unanswered threads
     * 
     * @return Illuminate\Database\Eloquent\Builder
     */
    protected function unanswered() {
        return $this->builder->where('replies_count', 0);
    }
}