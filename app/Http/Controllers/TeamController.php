<?php

namespace App\Http\Controllers;

use App\Team;
use App\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
     /*
    * --------------------------------------------------------------------------
    * @ Function Name            : getTeamsListData()
    * @ Added Date               : 17-06-2021
    * @ Added By                 : mahadev shetye
    * -----------------------------------------------------------------
    * @ Description              : This is to get all team data
    * -----------------------------------------------------------------
    * @ return                   : array
    * -----------------------------------------------------------------
    * @ Modified Date            :
    * @ Modified By              :
    *
    */
    public function getTeamsListData()
    {
        $teams = Team::get()->toArray();

        return response()->json([
            'success' => true,
            'data' => $teams
        ]);
    }

    /*
    * --------------------------------------------------------------------------
    * @ Function Name            : updateTeamData()
    * @ Added Date               : 17-06-2021
    * @ Added By                 : mahadev shetye
    * -----------------------------------------------------------------
    * @ Description              : This is to add new team data
    * @Param                     : name - name of the team,logo_url-tema log
    * -----------------------------------------------------------------
    * @ return                   : array
    * -----------------------------------------------------------------
    * @ Modified Date            :
    * @ Modified By              :
    *
    */
    public function addTeamData(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'logo_url' => 'required|mimes:jpg,jpeg,png|max:2048'
        ]);
        if (!$validator->fails()) {
            try {

                $logo_file = $request->logo_url->store('public/teams');

                $team_data = array(
                    'name'=>$request->name,
                    'logo_url'=>$logo_file
                );

                Team::insert($team_data);
                return response()->json([
                    'success' => true,
                    'message' => 'Team added successfully'
                ]);
            }
            catch (Throwable $e) {
                return response()->json([
                'success' => false,
                'message' => 'Exception error',
                'error_fields' => $e->errors(),
            ], 500);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'error' => $validator->errors(),
            ], 500);
        }
    }

    /*
    * --------------------------------------------------------------------------
    * @ Function Name            : updateTeamData()
    * @ Added Date               : 17-06-2021
    * @ Added By                 : mahadev shetye
    * -----------------------------------------------------------------
    * @ Description              : This is to update team data
    * @Param                     : team_id - id of the team 
    * -----------------------------------------------------------------
    * @ return                   : array
    * -----------------------------------------------------------------
    * @ Modified Date            :
    * @ Modified By              :
    *
    */
    public function updateTeamData(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'logo_url' => 'required',
            'team_id' => 'required|integer'
        ]);
        if (!$validator->fails()) {
            try {

                $logo_file = $request->logo_url->store('public/teams');
                $team_data = array(
                    'name'=>$request->name,
                    'logo_url'=>$request->logo_file
                );

                Team::where('id', $request->team_id)->update($team_data);
                return response()->json([
                    'success' => true,
                    'message' => 'Team updated successfully'
                ]);
            }
            catch (Throwable $e) {
                return response()->json([
                'success' => false,
                'message' => 'Exception error',
                'error_fields' => $e->errors(),
            ], 500);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'error' => $validator->errors(),
            ], 500);
        }
    }

     /*
    * --------------------------------------------------------------------------
    * @ Function Name            : deleteTeamData()
    * @ Added Date               : 17-06-2021
    * @ Added By                 : mahadev shetye
    * -----------------------------------------------------------------
    * @ Description              : This is to delete team data
    * @Param                     : team_id - id of the team 
    * -----------------------------------------------------------------
    * @ return                   : array
    * -----------------------------------------------------------------
    * @ Modified Date            :
    * @ Modified By              :
    *
    */
    public function deleteTeamData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'team_id' => 'required|integer'
        ]);
        if (!$validator->fails()) {
            try {
                $team = Team::find($request->team_id);

                if (!$team) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Team with id ' . $request->team_id . ' not found'
                    ], 400);
                }

                if ($team->delete()) {
                    return response()->json([
                        'success' => true
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Team could not be deleted'
                    ], 500);
                }
            }
            catch (Throwable $e) {
                return response()->json([
                'success' => false,
                'message' => 'Exception error',
                'error_fields' => $e->errors(),
            ], 500);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'error' => $validator->errors(),
            ], 500);
        }
    }

     /*
    * --------------------------------------------------------------------------
    * @ Function Name            : getTeamData()
    * @ Added Date               : 17-06-2021
    * @ Added By                 : mahadev shetye
    * -----------------------------------------------------------------
    * @ Description              : This is to get team data
    * @Param                     : team_id - id of the team 
    * -----------------------------------------------------------------
    * @ return                   : array
    * -----------------------------------------------------------------
    * @ Modified Date            :
    * @ Modified By              :
    *
    */
    public function getTeamData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'team_id' => 'required|integer'
        ]);
        if (!$validator->fails()) {
            try {
                $team = Team::find($request->team_id);

                if (!$team) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Team with id ' . $request->team_id . ' not found'
                    ], 400);
                }else{
                    return response()->json([
                        'success' => true,
                        'data' => $team->toArray()
                    ], 200);    
                }
            }
            catch (Throwable $e) {
                return response()->json([
                'success' => false,
                'message' => 'Exception error',
                'error_fields' => $e->errors(),
            ], 500);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'error' => $validator->errors(),
            ], 500);
        }

    }

     /*
    * --------------------------------------------------------------------------
    * @ Function Name            : getTeamPlayersListData()
    * @ Added Date               : 17-06-2021
    * @ Added By                 : mahadev shetye
    * -----------------------------------------------------------------
    * @ Description              : This is to get team player list
    * @Param                     : team_id - id of the team 
    * -----------------------------------------------------------------
    * @ return                   : array
    * -----------------------------------------------------------------
    * @ Modified Date            :
    * @ Modified By              :
    *
    */
    public function getTeamPlayersListData(Request $request){
         $validator = Validator::make($request->all(), [
            'team_id' => 'required|integer'
        ]);
        if (!$validator->fails()) {
            try {
                $team_players = Player::where('team_id',$request->team_id)->get();

                if (!$team_players) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No players found'
                    ], 400);
                }else{
                    return response()->json([
                        'success' => true,
                        'data' => $team_players->toArray()
                    ], 200);    
                }
            }
            catch (Throwable $e) {
                return response()->json([
                'success' => false,
                'message' => 'Exception error',
                'error_fields' => $e->errors(),
            ], 500);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'error' => $validator->errors(),
            ], 500);
        }   
    }

}
