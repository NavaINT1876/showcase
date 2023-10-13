<?php

namespace App\Services;

use App\Models\Todo;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TodoManager
{
    public function create(Request $request): void
    {
        $todo = new Todo();

        $todo->title = $request->title;
        if ($request->description) {
            $todo->description = $request->description;
        }

        $todo->save();
    }

    public function update(int $id, Request $request)
    {
        $todo = Todo::find($id);
        if (!$todo) {
            throw new NotFoundHttpException('Not Found');
        }

        $newFieldsParams = [
            'title' => $request->title,
        ];
        if ($request->description) {
            $newFieldsParams['description'] = $request->description;
        }

        $todo->update($newFieldsParams);
    }
}
