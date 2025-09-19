<?php

namespace App\Http\Controllers;

use App\Models\AppConfig;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AppConfigController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:configs-all');
    }

    public function index()
    {
        $configs = AppConfig::all();
        return Inertia::render('Config/Index', [
            'configs' => $configs,
        ]);
    }

    public function create()
    {
        return Inertia::render('Config/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'key'         => 'required|string|unique:app_configs,key',
            'value'       => 'nullable|string',
            'description' => 'nullable|string',
            'media'       => 'nullable|mimetypes:image/*,video/*,application/pdf|max:2048',
            'required'    => 'boolean',
        ]);

        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $path = $file->store('app-configs', 'public');
            $data['path_archive'] = $path;
            $data['extension'] = $file->getClientOriginalExtension();
        }

        $data['required'] = $request->boolean('required');

        AppConfig::create($data);

        return redirect()
            ->route('config.index')
            ->with('success', 'Configuração criada com sucesso.');
    }

    public function edit(AppConfig $config)
    {
        return Inertia::render('Config/Edit', [
            'config' => $config,
        ]);
    }

    public function update(Request $request, AppConfig $config): RedirectResponse
    {
        $data = $request->validate([
            'key'         => "required|string|unique:app_configs,key,{$config->id}",
            'value'       => 'nullable|string',
            'description' => 'nullable|string',
            'media'       => 'nullable|mimetypes:image/*,video/*,application/pdf|max:2048',
            'required'    => 'boolean',
        ]);

        if ($request->hasFile('media')) {
            if ($config->path_archive) {
                Storage::disk('public')->delete($config->path_archive);
            }
            $file = $request->file('media');
            $path = $file->store('app-configs', 'public');
            $data['path_archive'] = $path;
            $data['extension'] = $file->getClientOriginalExtension();
        }

        $data['required'] = $request->boolean('required');

        $config->update($data);

        return redirect()
            ->route('config.index')
            ->with('success', 'Configuração atualizada com sucesso.');
    }

    public function destroy(AppConfig $config): RedirectResponse
    {
        if ($config->path_archive) {
            Storage::disk('public')->delete($config->path_archive);
        }

        $config->delete();

        return redirect()
            ->route('config.index')
            ->with('success', 'Configuração excluída com sucesso.');
    }
}
