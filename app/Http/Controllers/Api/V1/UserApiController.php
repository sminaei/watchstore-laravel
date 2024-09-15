<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Repositories\UserRepository;
use App\Http\Resources\UserResource;
use App\Http\services\Keys;
use App\Models\User;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class UserApiController extends Controller
{
    /**
     * @OA\Post(
     ** path="/api/v1/register",
     *  tags={"User Api"},
     *     security ={{"sanctum":{}}},
     *  description="use the signin user with received code",
     * @OA\RequestBody(
     *    required=true,
     * *         @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *           @OA\Property(
     *                  property="image",
     *                  description="user image",
     *                  type="array",
     *
     *     @OA\Items(
     *     type="string",
     *      format="binary",
     * ),
     * ),
     *        @OA\Property(
     *                  property="phone",
     *                  description=" user phone number",
     *                  type="string",
     *               ),
     *              @OA\Property(
     *                  property="name",
     *                  description=" user name",
     *                  type="string",
     *               ),
     *         @OA\Property(
     *                  property="address",
     *                  description=" user address",
     *                  type="string",
     *               ),
     *       @OA\Property(
     *                  property="postal_code",
     *                  description="user postal code",
     *                  type="string",
     *               ),
     *       @OA\Property(
     *                  property="lat",
     *                  description="user location latitude",
     *                  type="double",
     *               ),
     *        @OA\Property(
     *                  property="lang",
     *                  description="user location longitude",
     *                  type="double",
     *               ),
     *     ),
     *   )
     * ),
     *   @OA\Response(
     *      response=200,
     *      description="Data Saved",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   )
     *)
     **/
   public function register(Request $request){

       $user =auth()->user();
//       2|11jmIdYPFloTnQJTrvDk2ajWB1ffxmaizBDXZUmqe74dc0ce
       $image = User::saveImage($request->file);
       if ($user){
           $user->update([
               'name' => $request->input('name'),
               'phone' => $request->input('phone'),
               'photo' => $image
           ]);
           $user->addresses()->create([
               'address' => $request->input('address'),
               'postal_code' => $request->input('postal_code'),
               'lat' => $request->input('lat'),
               'lang'=>$request->input('lang')
           ]);
           return Response()->json([
                  'result' => true,
                    'message' => 'user updated',
                    'data' => [
               'user' => new UserResource($user)
           ]
                ], 201);
       }else{
           return Response()->json([
               'result' => false,
               'message' => 'user not found',
               'data' => []
           ], 403);
       }
   }
    /**
     * @OA\Post(
     ** path="/api/v1/profile",
     *  tags={"User info"},
     *     security ={{"sanctum":{}}},
     *   @OA\Response(
     *      response=200,
     *      description="it's ok",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   )
     *)
     **/
   public function profile(Request $request){
       $user =auth()->user();
       return Response()->json([
           'result' => true,
           'message' => 'user profile ',
           'data' => [
               Keys::user => new UserResource($user),
               Keys::user_processing_count=> UserRepository::processingUserOrderCount($user),
               Keys::user_received_count=>UserRepository::receivedUserOrderCount($user),
               Keys::user_rejected_count=>UserRepository::rejectedUserOrderCount($user),

           ]
       ], 200);

   }
   public function receivedOrders(Request $request){
       $user =auth()->user();
       return Response()->json([
           'result' => true,
           'message' => 'user received orders ',
           'data' =>UserRepository::receivedUserOrder($user),
       ], 200);
   }
}
