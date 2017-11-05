<?php
/**
 * Created by PhpStorm.
 * User: Rafa
 * Date: 14/10/2017
 * Time: 20:41
 */

namespace App\Http\Controllers;

use App\Competency;
use Illuminate\Http\Request;



class ForgettingCurveController
{
    public function forgettingLevel(Request $request) {
        $competenceId = $request->get('competence_id');
        $userId= $request->get('user_id');
        return 1;
    }
}