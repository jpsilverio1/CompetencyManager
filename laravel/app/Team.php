<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class Team extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description',
    ];



    public function competencies()
    {
        $members=$this->teamMembers;
        $competences=new \Illuminate\Database\Eloquent\Collection();
        foreach($members as $m) {
            $competences=$competences->merge($m->competencies);
        }
        $perPage = 5;
        $pageName="competences";
        $page=LengthAwarePaginator::resolveCurrentPage($pageName)?:1;
        $pagination = new LengthAwarePaginator(
            $competences->forPage($page, $perPage),
            $competences->count(),
            $perPage,
            $page,
            ['pageName' => $pageName, 'path' => Paginator::resolveCurrentPath()]
        );
        return $pagination;
    }


    public function teamMembers()
    {
        return $this->belongsToMany('App\User', 'team_members');
    }

    public function members() {
        return $this->belongsToMany('App\User', 'task_team_members');
    }
}
