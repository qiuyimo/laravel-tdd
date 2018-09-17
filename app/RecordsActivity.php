<?php

namespace App;

trait RecordsActivity
{
    /**
     * 设定模型事件.
     * @return bool
     */
    protected static function bootRecordsActivity()
    {
        if (auth()->guest()) {
            return false;
        }
        foreach (static::getActivitiesToRecord() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }
    }

    /**
     * 定义需要的模型事件. 这些事件触发了都会执行指定的方法. 这里是 recordActivity.
     * @return array
     */
    protected static function getActivitiesToRecord()
    {
        return ['created'];
    }

    /**
     * 触发了事件需要执行的方法.
     * @param $event
     */
    protected function recordActivity($event)
    {
        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event),
            // 'subject_id' => $this->id,
            // 'subject_type' => get_class($this)
        ]);
    }

    /**
     * 模型多态关联.
     * @return mixed
     */
    protected function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }

    /**
     * 通过反射, 获取多态的类型名称.
     * @param $event
     * @return string
     * @throws \ReflectionException
     */
    protected function getActivityType($event)
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());

        return "{$event}_{$type}";
    }
}
