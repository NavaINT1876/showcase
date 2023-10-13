<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Services\TodoManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TodoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/todos",
     *     security={{"bearerAuth"={}}},
     *     summary="Get list of all TODO items",
     *     @OA\Response(
     *         response=200,
     *         description="List of TODO items"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized. No token has been provided or it was malformed."
     *     ),
     * )
     */
    public function list()
    {
        return response()->json(['todos' => Todo::all()]);
    }

    /**
     * @OA\Get(
     *     path="/api/todos/{id}",
     *     summary="View single TODO item",
     *     security={{"bearerAuth"={}}},
     *     @OA\Parameter(
     *         description="TODO item Id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized. No token has been provided or it was malformed."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found. TODO item was not found in database"
     *     )
     * )
     */
    public function view(int $id)
    {
        $todo = Todo::find($id);
        if (!$todo) {
            return response()->json(['errors' => 'Not Found'], ResponseAlias::HTTP_BAD_REQUEST);
        }

        return response()->json($todo);
    }

    /**
     * @OA\Post(
     *     path="/api/todos",
     *     summary="Creates a TODO item",
     *     security={{"bearerAuth"={}}},
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="title",
     *                      type="string",
     *                      description="title"
     *                  ),
     *                  @OA\Property(
     *                      property="description",
     *                      type="string",
     *                      description="description"
     *                  )
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized. No token has been provided or it was malformed."
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created. Changes successfully saved."
     *     )
     * )
     */
    public function create(Request $request, TodoManager $todoManager)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:to_do_ihor|max:255',
            'description' => 'max:4000',
        ]);
        if ($validator->errors()->count()) {
            return response()->json(['errors' => $validator->errors()], ResponseAlias::HTTP_BAD_REQUEST);
        }

        $todoManager->create($request);

        return response()->noContent(ResponseAlias::HTTP_CREATED);
    }

    /**
     * @OA\Put(
     *     path="/api/todos/{id}",
     *     summary="Updates a TODO item",
     *     security={{"bearerAuth"={}}},
     *     @OA\Parameter(
     *         description="TODO item Id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         @OA\Examples(example="int", value="1", summary="An integer value.")
     *     ),
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="title",
     *                      type="string",
     *                      description="title"
     *                  ),
     *                  @OA\Property(
     *                      property="description",
     *                      type="string",
     *                      description="description"
     *                  )
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized. No token has been provided or it was malformed."
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No Content. Changes successfully saved."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found. TODO item was not found in database"
     *     )
     * )
     */
    public function edit(int $id, Request $request, TodoManager $todoManager)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:to_do_ihor|max:255',
            'description' => 'max:4000',
        ]);
        if ($validator->errors()->count()) {
            return response()->json(['errors' => $validator->errors()], ResponseAlias::HTTP_BAD_REQUEST);
        }

        try {
            $todoManager->update($id, $request);
        } catch (NotFoundHttpException $exception) {
            return response()->json(['errors' => $exception->getMessage()], ResponseAlias::HTTP_NOT_FOUND);
        } catch (\Throwable $exception) {
            return response()->json(['errors' => 'Unexpected error'], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->noContent(ResponseAlias::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Delete(
     *     path="/api/todos/{id}",
     *     summary="Delete single TODO item",
     *     security={{"bearerAuth"={}}},
     *     @OA\Parameter(
     *         description="TODO item Id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No Content"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found. TODO item was not found in database"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized. No token has been provided or it was malformed."
     *     )
     * )
     */
    public function delete(int $id)
    {
        $todo = Todo::find($id);
        if (!$todo) {
            return response()->json(['errors' => 'Not Found'], ResponseAlias::HTTP_NOT_FOUND);
        }

        $todo->delete();

        return response()->noContent(ResponseAlias::HTTP_NO_CONTENT);
    }
}
