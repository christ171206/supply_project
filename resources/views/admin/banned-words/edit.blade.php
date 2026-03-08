@extends('layouts.admin')

@section('title', 'Modifier Mot Bannissant')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('admin.banned-words.index') }}" class="btn btn-link">← Retour</a>
        <h1 class="h3 d-inline-block">✏️ Modifier Mot Bannissant</h1>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.banned-words.update', $word) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label"><strong>Mot</strong> <span class="text-danger">*</span></label>
                            <input type="text" name="word" class="form-control @error('word') is-invalid @enderror" 
                                   value="{{ old('word', $word->word) }}" required>
                            @error('word')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><strong>Description</strong></label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                      rows="3">{{ old('description', $word->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><strong>Sévérité</strong> <span class="text-danger">*</span></label>
                            <select name="severity" class="form-select @error('severity') is-invalid @enderror" required>
                                <option value="low" @selected(old('severity', $word->severity) === 'low')>🔵 Basse</option>
                                <option value="medium" @selected(old('severity', $word->severity) === 'medium')>🟡 Moyenne</option>
                                <option value="high" @selected(old('severity', $word->severity) === 'high')>🔴 Haute</option>
                            </select>
                            @error('severity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" 
                                   value="1" @checked(old('is_active', $word->is_active))>
                            <label class="form-check-label" for="is_active">
                                <strong>Actif</strong>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">💾 Mettre à jour</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">📋 Informations</h5>
                </div>
                <div class="card-body">
                    <p><strong>Créé :</strong> {{ $word->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Modifié :</strong> {{ $word->updated_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Créé par :</strong> {{ $word->createdByAdmin->name ?? 'Système' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
