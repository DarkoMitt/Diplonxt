<?php
namespace App\Http\Controllers;
use App\Http\Requests\StoreThesisVersionRequest;
use App\Models\Thesis;
use App\Models\ThesisVersion;
use App\Notifications\ThesisActivityNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
class ThesisVersionController extends Controller
{
    public function store(StoreThesisVersionRequest $request, Thesis $thesis): RedirectResponse
    {
        $file = $request->file('document'); $path = $file->store("theses/{$thesis->id}", 'local');
        $version = $thesis->versions()->create(['uploaded_by' => $request->user()->id, 'version_number' => ((int) $thesis->versions()->max('version_number')) + 1, 'file_path' => $path, 'original_file_name' => $file->getClientOriginalName(), 'mime_type' => $file->getMimeType(), 'file_size' => $file->getSize(), 'notes' => $request->validated('notes')]);
        if ($thesis->professor) { $thesis->professor->notify(new ThesisActivityNotification('New thesis version', "{$thesis->student->name} uploaded version {$version->version_number}.", route('theses.show', $thesis))); }
        return back()->with('success', "Version {$version->version_number} uploaded successfully.");
    }
    public function download(Thesis $thesis, ThesisVersion $version): StreamedResponse
    {
        $this->authorize('view', $thesis); abort_unless($version->thesis_id === $thesis->id, 404); abort_unless(Storage::disk('local')->exists($version->file_path), 404);
        return Storage::disk('local')->download($version->file_path, $version->original_file_name);
    }
}
