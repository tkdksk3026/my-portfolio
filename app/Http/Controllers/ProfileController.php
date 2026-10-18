<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * プロフィール編集画面を表示
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * ユーザーのプロフィール情報を更新
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // フォームリクエストでバリデーション済みのデータのみを抽出して反映
        $request->user()->fill($request->validated());

        // メールアドレス変更時はセキュリティ上の理由からメール再認証が必要になるため、認証日時をリセット
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * ユーザーアカウントの削除（退会処理）
     */
    public function destroy(Request $request): RedirectResponse
    {
        // 誤操作・なりすまし防止のため、現在のパスワード再入力による本人確認を実施
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // ログアウト処理後にユーザーレコードを削除
        Auth::logout();
        $user->delete();

        // セッション固定攻撃（Session Fixation）を防止するため、既存セッションの破棄とCSRFトークンの再生成を実行
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
