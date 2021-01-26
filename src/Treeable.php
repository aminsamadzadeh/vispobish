<?php

namespace AminSamadzadeh\Vispobish;

trait Treeable
{
    public static function boot()
    {
        parent::boot();

        self::creating(function($model){
            $model->setPathID();
        });

        self::updating(function($model){
            $model->setPathID();
        });

        self::updated(function($model){
            $model->updateChildrenPath();
        });
    }


    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function descendants()
    {
        return
            $this->newQuery()
                ->where('path', 'like', "%/{$this->id}/%")
                ->orWhere('path', 'like', "%/{$this->id}");
    }

    public function ancestors()
    {
        return
            $this->newQuery()
                ->whereIn('id', $this->parentIds());
    }

    public function parentIds()
    {
        return array_filter(explode('/', $this->path));
    }

    protected function setPathID()
    {
        $this->path = $this->pathID();
        if(isset($this->pathNamedWith))
            $this->named_path = $this->namedPath();
    }

    public function pathID()
    {
        if(is_null($this->parent_id))
            return;
        return $this->parent->pathID()."/".$this->parent_id;
    }

    public function namedPath()
    {
        if(is_null($this->parent_id))
            return $this->{$this->pathNamedWith};
        return $this->parent->namedPath()."/".$this->{$this->pathNamedWith};
    }

    public function updateChildrenPath()
    {
        foreach($this->descendants()->get() as $child)
        {
            $child->setPathID();
            $child->save();
        }
    }

    public function isRoot()
    {
        return is_null($this->parent_id);
    }

    public function scopeRoots($query)
    {
        $query->whereNull('parent_id');
    }

}
