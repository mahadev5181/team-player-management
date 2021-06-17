<?php

namespace App\Http\Controllers;

use App\Player;
use App\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlayerController extends Controller
{
    /*
    * --------------------------------------------------------------------------
    * @ Function Name            : getPlayersListData()
    * @ Added Date               : 17-06-2021
    * @ Added By                 : mahadev shetye
    * -----------------------------------------------------------------
    * @ Description              : This is to get all player data
    * -----------------------------------------------------------------
    * @ return                   : array
    * -----------------------------------------------------------------
    * @ Modified Date            :
    * @ Modified By              :
    *
    */
    public function getPlayersListData()
    {
        $players = Player::leftJoin('teams', 'players.team_id', '=', 'teams.id')
                    ->select('players.id as player_id','players.first_name', 'players.last_name', 'players.profile_url', 'teams.name as team','teams.id as team_id')->get();

       
        return response()->json([
            'success' => true,
            'data' => $players->toArray()
        ]);
    }

    /*
    * --------------------------------------------------------------------------
    * @ Function Name            : addPlayerData()
    * @ Added Date               : 17-06-2021
    * @ Added By                 : mahadev shetye
    * -----------------------------------------------------------------
    * @ Description              : This is to add new player data
    * @Param                     : first_name - first name of the player,last_name - last name of the player,team_id - team id of the player,profile_url-player log
    * -----------------------------------------------------------------
    * @ return                   : array
    * -----------------------------------------------------------------
    * @ Modified Date            :
    * @ Modified By              :
    *
    */
    public function addPlayerData(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'team_id' => 'integer',
            'profile_url' => 'required|mimes:jpg,jpeg,png|max:2048'
        ]);
        if (!$validator->fails()) {
            try {

                $profile_file = $request->profile_url->store('public/players');

                $player_data = array(
                    'first_name'=>$request->first_name,
                    'last_name'=>$request->last_name,
                    'team_id'=>$request->team_id,
                    'profile_url'=>$profile_file
                );

                $team = Team::find($request->team_id);

                if (!$team) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Team with id ' . $request->team_id . ' not found'
                    ], 400);
                }

                Player::insert($player_data);
                return response()->json([
                    'success' => true,
                    'message' => 'Player added successfully'
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
    * @ Function Name            : updatePlayerData()
    * @ Added Date               : 17-06-2021
    * @ Added By                 : mahadev shetye
    * -----------------------------------------------------------------
    * @ Description              : This is to update player data
    * @Param                     : first_name - first name of the player,last_name - last name of the player,team_id - team id of the player,player_id- id of the pplayer to be updated
    * -----------------------------------------------------------------
    * @ return                   : array
    * -----------------------------------------------------------------
    * @ Modified Date            :
    * @ Modified By              :
    *
    */

    public function updatePlayerData(Request $request)
    {

        $validator = Validator::make($request->all(), [
           'first_name' => 'required|string',
            'last_name' => 'required|string',
            'team_id' => 'integer',
            'player_id' => 'required|integer',
        ]);
        if (!$validator->fails()) {
            try {

                $player_data = array(
                    'first_name'=>$request->first_name,
                    'last_name'=>$request->last_name,
                    'team_id'=>$request->team_id
                );

                $team = Team::find($request->team_id);

                if (!$team) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Team with id ' . $request->team_id . ' not found'
                    ], 400);
                }

                Player::where('id', $request->player_id)->update($player_data);
                return response()->json([
                    'success' => true,
                    'message' => 'Player updated successfully'
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
    * @ Function Name            : deletePlayerData()
    * @ Added Date               : 17-06-2021
    * @ Added By                 : mahadev shetye
    * -----------------------------------------------------------------
    * @ Description              : This is to delete player data
    * @Param                     : player_id- id of the pplayer to be deleted
    * -----------------------------------------------------------------
    * @ return                   : array
    * -----------------------------------------------------------------
    * @ Modified Date            :
    * @ Modified By              :
    *
    */
    public function deletePlayerData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'player_id' => 'required|integer'
        ]);
        if (!$validator->fails()) {
            try {
                $player = Player::find($request->player_id);

                if (!$player) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Player with id ' . $request->player_id . ' not found'
                    ], 400);
                }

                if ($player->delete()) {
                    return response()->json([
                        'success' => true
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Player could not be deleted'
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
    * @ Function Name            : getPlayerData()
    * @ Added Date               : 17-06-2021
    * @ Added By                 : mahadev shetye
    * -----------------------------------------------------------------
    * @ Description              : This is to get single player data
    * @Param                     : player_id- id of the pplayer 
    * -----------------------------------------------------------------
    * @ return                   : array
    * -----------------------------------------------------------------
    * @ Modified Date            :
    * @ Modified By              :
    *
    */
    public function getPlayerData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'player_id' => 'required|integer'
        ]);
        if (!$validator->fails()) {
            try {
                $player = Player::leftJoin('teams', 'players.team_id', '=', 'teams.id')
                    ->select('players.id as player_id','players.first_name', 'players.last_name', 'players.profile_url', 'teams.name as team','teams.id as team_id')->find($request->player_id);

                if (!$player) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Player with id ' . $request->player_id . ' not found'
                    ], 400);
                }else{
                    return response()->json([
                        'success' => true,
                        'data' => $player->toArray()
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
