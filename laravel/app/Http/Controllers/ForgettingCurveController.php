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
        /*
        $endorsedUserId = $request->get('endorsed_user_id');
        $endorser = \Auth::user();
        $endorser->addEndorsement($endorsedUserId, $competenceId, $competenceLevel);
        */
        return /*redirect("users/$endorsedUserId")*/ 1;
    }
}