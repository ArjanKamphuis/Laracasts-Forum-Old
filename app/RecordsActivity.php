<?php

namespace App;

use ReflectionClass;

trait RecordsActivity
{
    protected static function bootRecordsActivity() {
        if (auth()->guest()) return;
        
        foreach (static::getActivitiesToRecord() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }
    }

    protected static function getActivitiesToRecord() {
        return ['created'];
    }

    /**
     * @param  string $event
     * @return -
     */
    protected function recordActivity(string $event) {
        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event),
        ]);
    }

    public function activity() {
        return $this->morphMany('App\Activity', 'subject');
    }

    /**
     * @param  string $event
     * @return string event's class name
     */
    protected function getActivityType(string $event) {
        $type = strtolower((new ReflectionClass($this))->getShortName());
        return "{$event}_{$type}";
    }
}