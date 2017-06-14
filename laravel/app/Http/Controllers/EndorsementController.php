<?php
/**
 * Created by PhpStorm.
 * User: Jéssica
 * Date: 2017-06-14
 * Time: 12:34 PM
 */

namespace App\Http\Controllers;


class EndorsementController
{
    public function show($endorsedId, $competenceId)
    {
        //
        $competenceLevel = "legal";
        $endorser = \Auth::user();
        $endorser->addEndorsement($endorsedId, $competenceId, $competenceLevel);

        return redirect("users/$endorsedId");
    }

}