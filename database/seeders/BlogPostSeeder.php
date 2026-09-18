<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::where('email', 'contact@francislandford.com')->first();

        $body = <<<'MD'
Cargo and vessel boarding operations don't stop for a bad internet connection. Port staff move between vessels, warehouses, and dockside checkpoints where signal is patchy at best, so when Bam Global brought us in to build DockMaster — a system for managing cargo and vessel boarding operations — the first real design decision wasn't which framework to use. It was: what happens when the network disappears mid-task?

Local-first, not "offline as an afterthought"

A lot of systems bolt offline support on after the fact — cache a few screens, show a "you're offline" banner, and hope for the best. That approach falls apart fast in this domain, because boarding and cargo tracking are exactly the operations that happen furthest from a reliable connection. So DockMaster was built local-first from day one: every action a port officer takes — logging a vessel arrival, recording cargo onboarded or offloaded, checking a crew member in — writes to a local store immediately and queues for sync. The app never blocks on the network, because the network was never assumed to be there.

The sync queue

Every locally-recorded action gets wrapped as a discrete, idempotent operation with a client-generated identifier before it's queued. That idempotency matters more than it sounds like it should: on a flaky connection, a sync request can time out on the client side even after the server successfully processed it. Without idempotent operations, a retry means double-counted cargo or duplicate boarding records — exactly the kind of error that erodes trust in a system meant to be a source of truth. With them, a retry is just a no-op the second time around.

Conflict resolution that respects the domain

Multiple officers can be working the same vessel at once, which means conflicting writes are a certainty, not an edge case. Generic "last write wins" resolution is tempting because it's simple, but it's wrong here — a cargo count correction shouldn't silently overwrite an unrelated boarding entry just because they touched the same vessel record around the same time. DockMaster resolves conflicts at the level of the individual operation rather than the whole record, so two officers working different parts of the same vessel's manifest don't step on each other's changes. It's more bookkeeping than a naive merge strategy, but it's the difference between a sync engine people trust and one they quietly work around.

What made it to the mobile side

DockMaster Mobile carries the same philosophy: the mobile client isn't a thin window onto a server — it holds its own local copy of the data it needs, applies the same idempotent-operation model, and reconciles automatically the moment connectivity returns. Officers don't think about sync at all; they just work, and the system catches up when it can.

The takeaway

None of this is exotic — sync queues, idempotent operations, and field-level conflict resolution are well-trodden patterns. The real lesson from DockMaster was about sequencing: treating offline-first as the foundation instead of a feature meant every later decision (data model, API shape, even how errors surface in the UI) was made in service of "this has to work without a connection," rather than retrofitted around it. That's a much easier system to build correctly, and a much easier one to trust in production.
MD;

        $post = Post::updateOrCreate(
            ['title' => 'Building an Offline-First Sync Engine for DockMaster'],
            [
                'author_id' => $author?->id,
                'excerpt' => "Cargo and vessel boarding don't stop for bad internet. Here's how DockMaster's offline-first sync engine — built for Bam Global — keeps port operations running with unreliable connectivity.",
                'body' => $body,
                'ai_summary' => 'A look at the offline-first architecture behind DockMaster: local-first writes, an idempotent sync queue, and field-level conflict resolution for cargo and vessel boarding operations that need to keep working without a reliable connection.',
                'status' => 'published',
                'published_at' => now(),
            ]
        );

        $category = Category::firstOrCreate(['type' => 'blog', 'name' => 'Engineering']);
        $post->categories()->syncWithoutDetaching([$category->id]);

        $tagNames = ['Offline-First', 'Mobile', 'System Design', 'Laravel'];
        $tagIds = collect($tagNames)->map(fn (string $name) => Tag::firstOrCreate(['name' => $name])->id);
        $post->tags()->syncWithoutDetaching($tagIds);
    }
}
