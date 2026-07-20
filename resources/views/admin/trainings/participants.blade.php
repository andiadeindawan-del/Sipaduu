/**
 * Get participants list for a training.
 * PERBAIKAN: Gunakan registrations
 */
public function participants(Training $training)
{
    $query = $training->registrations()
        ->with(['user'])
        ->whereIn('status', ['pending', 'disetujui', 'ditolak', 'dibatalkan']);

    // Search
    if (request()->filled('search')) {
        $search = request()->search;
        $query->whereHas('user', function($q) use ($search) {
            $q->where('name', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%");
        });
    }

    $participants = $query->orderBy('created_at', 'desc')
                          ->paginate(15)
                          ->withQueryString();
    
    return view('admin.trainings.participants', compact('training', 'participants'));
}