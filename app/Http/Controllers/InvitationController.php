<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Home;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class InvitationController extends Controller
{
    public function invite(Request $request, Home $home)
    {
        $request->validate([
            'email' => 'required|email',
            'role' => 'required|in:owner,editor,viewer',
        ]);

        $invitation = Invitation::create([
            'home_id' => $home->id,
            'email' => $request->email,
            'role' => $request->role,
            'token' => Str::random(32),
            'expires_at' => now()->addDays(7),
        ]);

        $url = URL::temporarySignedRoute(
            'invitations.accept',
            now()->addDays(7),
            ['token' => $invitation->token]
        );

        // In a real app, send email here.
        // Mail::to($request->email)->send(new HomeInvitation($url));

        return back()->with('status', 'Invitation sent: ' . $url);
    }

    public function accept(Request $request, $token)
    {
        $invitation = Invitation::where('token', $token)
            ->where('expires_at', '>', now())
            ->firstOrFail();

        $user = Auth::user();

        // If not logged in, redirect to register/login with intended URL
        if (!$user) {
            session(['url.intended' => $request->fullUrl()]);
            return redirect()->route('register')->with('status', '招待を承諾するには、アカウントの作成またはログインを行ってください。');
        }

        // In multi-tenant systems, we allow anyone with the link to join if email is null
        if ($invitation->email && $user->email !== $invitation->email) {
            return redirect()->route('dashboard')->with('error', 'この招待は別のメールアドレス向けです。');
        }

        $user->homes()->syncWithoutDetaching([
            $invitation->home_id => ['role' => $invitation->role]
        ]);

        $invitation->delete();

        return redirect()->route('dashboard')->with('status', '家に参加しました！');
    }
}
