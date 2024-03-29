<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(ContactRequest $request)
    {
        $data = $request->validated();
        $contact = new Contact($data);
        $contact->save();

        return (new ContactResource($contact))->response()->setStatusCode(201);
    }

    public function index(Request $request)
    {
        $query = Contact::query();

        if ($request->has('name')) {
            $query->where('first_name', 'like', '%' . $request->input('name') . '%')
                  ->orWhere('last_name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->has('phone')) {
            $query->where('phone', 'like', '%' . $request->input('phone') . '%');
        }

        if ($request->has('email')) {
            $query->where('email', 'like', '%' . $request->input('email') . '%');
        }

        $size = $request->input('size', 10);
        $contacts = $query->paginate($size);

        return response()->json([
            'data' => ContactResource::collection($contacts->items()),
            'errors' => (object)[],
            'meta' => [
                'total' => $contacts->total(),
                'size' => $contacts->perPage(),
                'current_page' => $contacts->currentPage(),
                'last_page' => $contacts->lastPage()
            ]
        ]);
    }

    public function show($id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json([
                'errors' => [
                    'message' => 'Contact not found'
                ]
            ], 404);
        }

        return response()->json([
            'data' => new ContactResource($contact),
            'errors' => (object)[]
        ]);
    }

    public function update(ContactRequest $request, $id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json([
                'errors' => [
                    'message' => 'Contact not found'
                ]
            ], 404);
        }

        $data = $request->validated();
        $contact->update($data);

        return response()->json([
            'data' => new ContactResource($contact),
            'errors' => (object)[]
        ]);
    }

    public function destroy($id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json([
                'errors' => [
                    'message' => 'Contact not found'
                ]
            ], 404);
        }

        $contact->delete();

        return response()->json([
            'data' => true,
            'errors' => (object)[]
        ]);
    }
}
