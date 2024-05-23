<?php

namespace App\Http\Controllers;

use App\Http\Requests\PetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PetController extends Controller
{
    private $apiUrl = 'https://petstore.swagger.io/v2';

    public function index(Request $request)
    {
        $status = $request->query('status', 'available');

        $response = Http::get("$this->apiUrl/pet/findByStatus", ['status' => $status]);

        if ($response->successful()) {
            $pets = $response->json();
        } else {
            $error = $response->json()['message'];
            return redirect()->back()->withErrors("Failed to fetch pets: $error");
        }

        return view('pets.index', compact('pets', 'status'));
    }

    public function create()
    {
        return view('pets.create');
    }

    public function store(PetRequest $request)
    {
        // Pobierz dane z żądania
        $data = $request->only(['name', 'status', 'category_name', 'tag_name']);

        // Utwórz zwierzę w sklepie
        $petResponse = Http::post("{$this->apiUrl}/pet", [
            'id' => 0, // Serwer wygeneruje ID
            'category' => [
                'id' => 0, // Serwer wygeneruje ID
                'name' => $data['category_name']
            ],
            'name' => $data['name'],
            'photoUrls' => [],
            'tags' => [
                [
                    'id' => 0, // Serwer wygeneruje ID
                    'name' => $data['tag_name']
                ]
            ],
            'status' => $data['status']
        ]);

        if (!$petResponse->successful()) {
            $error = $petResponse->json()['message'] ?? 'Unknown error';
            return redirect()->back()->withErrors("Failed to create pet: $error");
        }

        $pet = $petResponse->json();

        // Prześlij obrazek, jeśli został przesłany
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoResponse = Http::attach('file', file_get_contents($photo->getRealPath()), $photo->getClientOriginalName())
                ->post("{$this->apiUrl}/pet/{$pet['id']}/uploadImage");

            if (!$photoResponse->successful()) {
                $error = $photoResponse->json()['message'] ?? 'Unknown error';
                return redirect()->back()->withErrors("Failed to upload pet image: $error");
            }

            // Zaktualizuj URL zdjęcia
            $photoUrl = $photoResponse->json()['message'];
            $pet['photoUrls'][] = $photoUrl;
        }

        return redirect()->route('pets.show', $pet['id'])->with('status', 'Pet added successfully!');
    }

    public function show($id)
    {
        $response = Http::get("$this->apiUrl/pet/$id");

        if ($response->successful()) {
            $pet = $response->json();
            return view('pets.show', compact('pet'));
        } else {
            $error = $response->json()['message'];
            return redirect()->back()->withErrors("Failed to fetch pet details: $error");
        }
    }

    public function edit(int $id)
    {
        $response = Http::get("{$this->apiUrl}/pet/{$id}");

        if ($response->successful()) {
            $pet = $response->json();
        } else {
            $error = $response->json()['message'];
            return redirect()->back()->withErrors("Failed to fetch pet details: $error");
        }

        return view('pets.edit', compact('pet'));
    }

    public function update(PetRequest $request, int $id)
    {
        // Pobierz dane z żądania
        $data = $request->only(['name', 'status', 'category_name', 'tag_name']);

        // Prześlij obrazek, jeśli został przesłany
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoResponse = Http::attach('file', file_get_contents($photo->getRealPath()), $photo->getClientOriginalName())
                ->post("{$this->apiUrl}/pet/{$id}/uploadImage");

            if (!$photoResponse->successful()) {
                $error = $photoResponse->json()['message'] ?? 'Unknown error';
                return redirect()->back()->withErrors("Failed to upload pet image: $error");
            }

            // Zaktualizuj URL zdjęcia
            $photoUrl = $photoResponse->json()['message'];
            $data['photoUrls'] = [$photoUrl];
        } else {
            $data['photoUrls'] = []; // Zakładamy, że brak zmiany zdjęcia pozostawia poprzednie.
        }

        // Aktualizuj zwierzaka w sklepie
        $updateResponse = Http::put("{$this->apiUrl}/pet", [
            'id' => $id,
            'category' => [
                'id' => 0, // Serwer może wygenerować ID lub użyj istniejącego ID
                'name' => $data['category_name']
            ],
            'name' => $data['name'],
            'photoUrls' => $data['photoUrls'],
            'tags' => [
                [
                    'id' => 0, // Serwer może wygenerować ID lub użyj istniejącego ID
                    'name' => $data['tag_name']
                ]
            ],
            'status' => $data['status']
        ]);

        if (!$updateResponse->successful()) {
            $error = $updateResponse->json()['message'] ?? 'Unknown error';
            return redirect()->back()->withErrors("Failed to update pet: $error");
        }

        return redirect()->route('pets.show', $id)->with('status', 'Pet updated successfully!');
    }

    public function destroy(int $id)
    {
        $response = Http::delete("{$this->apiUrl}/pet/{$id}");

        if ($response->successful()) {
            return redirect()->route('pets.index')->with('status', 'Pet deleted successfully!');
        } else {
            $error = $response->json()['message'] ?? 'Unknown error';
            return redirect()->back()->withErrors("Failed to delete pet: $error");
        }
    }

}
