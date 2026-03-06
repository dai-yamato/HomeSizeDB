<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>HomeSize DB - 住宅のあらゆる「寸法」をスマートに管理</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { font-family: 'Outfit', 'Noto Sans JP', sans-serif; }
            .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.3); }
            .text-gradient { background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        </style>
    </head>
    <body class="antialiased bg-slate-50 text-slate-900 overflow-x-hidden">
        <!-- Decoration Gradient -->
        <div class="fixed top-0 right-0 -z-10 w-[800px] h-[800px] bg-indigo-100/50 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/3"></div>
        <div class="fixed bottom-0 left-0 -z-10 w-[600px] h-[600px] bg-sky-100/40 rounded-full blur-[100px] translate-y-1/2 -translate-x-1/4"></div>

        <!-- Navigation -->
        <nav class="fixed top-0 w-full z-50 px-6 py-4">
            <div class="max-w-7xl mx-auto flex items-center justify-between glass px-6 py-3 rounded-[2rem] shadow-sm">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-200">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </div>
                    <span class="text-xl font-black tracking-tighter">HomeSize DB</span>
                </div>
                
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-bold text-slate-600 hover:text-indigo-600 transition-colors">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 hover:text-indigo-600 transition-colors">ログイン</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-full shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all">新規登録</a>
                        @endif
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="pt-40 pb-20 px-6">
            <div class="max-w-7xl mx-auto text-center">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-600 rounded-full text-xs font-black uppercase tracking-widest mb-8">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-600"></span>
                    </span>
                    Mobile First Design
                </div>
                
                <h1 class="text-5xl md:text-7xl font-black tracking-tight mb-8 leading-[1.1]">
                    住まいのあらゆる「寸法」を<br>
                    <span class="text-gradient">スマートに持ち歩こう。</span>
                </h1>
                
                <p class="max-w-2xl mx-auto text-lg text-slate-500 font-medium mb-12 leading-relaxed">
                    引越し、リフォーム、家具選び。あの場所のサイズを忘れて困ったことはありませんか？ HomeSize DB は、あなたの家の「内寸法」を直感的に記録し、いつでも取り出せるようにするデジタル計測ノートです。
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-10 py-5 bg-indigo-600 text-white font-black rounded-3xl shadow-2xl shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-1 transition-all text-xl">
                        今すぐ無料で始める
                    </a>
                    <a href="#features" class="w-full sm:w-auto px-10 py-5 bg-white text-slate-600 font-black rounded-3xl border border-slate-200 hover:bg-slate-50 transition-all text-xl">
                        機能を詳しく見る
                    </a>
                </div>
            </div>
        </section>

        <!-- Mockup Display -->
        <section class="py-10 px-6">
            <div class="max-w-5xl mx-auto relative">
                <div class="aspect-[16/10] bg-slate-900 rounded-[3rem] shadow-[0_50px_100px_-20px_rgba(0,0,0,0.3)] border-[8px] border-slate-800 overflow-hidden relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/20 to-purple-500/20"></div>
                    <!-- Sample UI Mock (Abstract) -->
                    <div class="p-10 flex gap-6 h-full items-center justify-center">
                        <div class="w-64 h-96 bg-white rounded-3xl shadow-2xl rotate-[-5deg] p-6 space-y-4 shrink-0">
                            <div class="flex justify-between items-center mb-8">
                                <span class="font-black text-indigo-600">1F / リビング</span>
                                <div class="w-8 h-8 bg-slate-100 rounded-full"></div>
                            </div>
                            <div class="h-40 bg-slate-100 rounded-2xl flex items-center justify-center text-slate-300">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div class="space-y-2">
                                <div class="h-6 bg-slate-50 rounded-lg flex items-center justify-between px-3">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">幅</span>
                                    <span class="text-sm font-black text-slate-800">1,800mm</span>
                                </div>
                                <div class="h-6 bg-slate-50 rounded-lg flex items-center justify-between px-3">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">高さ</span>
                                    <span class="text-sm font-black text-slate-800">2,400mm</span>
                                </div>
                            </div>
                        </div>
                        <div class="hidden md:block w-72 h-[450px] bg-white rounded-[2.5rem] shadow-2xl rotate-[5deg] p-8 -mt-10 overflow-hidden">
                             <div class="bg-indigo-600 h-10 w-full rounded-xl mb-6"></div>
                             <div class="space-y-6">
                                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                    <div class="w-12 h-3 bg-slate-200 rounded-full mb-3"></div>
                                    <div class="h-4 bg-indigo-100/50 w-full rounded-lg"></div>
                                </div>
                                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                    <div class="w-12 h-3 bg-slate-200 rounded-full mb-3"></div>
                                    <div class="h-4 bg-slate-100 w-3/4 rounded-lg"></div>
                                </div>
                                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                    <div class="w-12 h-3 bg-slate-200 rounded-full mb-3"></div>
                                    <div class="h-4 bg-slate-100 w-full rounded-lg"></div>
                                </div>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section id="features" class="py-32 px-6">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    <!-- Feature 1 -->
                    <div class="space-y-6">
                        <div class="w-16 h-16 bg-white rounded-[1.5rem] shadow-xl shadow-slate-100 flex items-center justify-center">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black tracking-tight">直感的なドリルダウン構造</h3>
                        <p class="text-slate-500 font-medium leading-relaxed">
                            階から部屋、そしてクローゼットや窓枠といった具体的な場所へ。スマホ片手にサクサク辿り着ける階層構造を採用しています。
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="space-y-6">
                        <div class="w-16 h-16 bg-white rounded-[1.5rem] shadow-xl shadow-slate-100 flex items-center justify-center">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black tracking-tight">写真で残す、場所の記憶</h3>
                        <p class="text-slate-500 font-medium leading-relaxed">
                            「どこの幅だっけ？」をゼロに。各場所に写真を登録して、寸法データとセットで視覚的に管理できます。
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="space-y-6">
                        <div class="w-16 h-16 bg-white rounded-[1.5rem] shadow-xl shadow-slate-100 flex items-center justify-center">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black tracking-tight">家族やパートナーと共有</h3>
                        <p class="text-slate-500 font-medium leading-relaxed">
                            招待URLを送るだけで、家全体の寸法データを共有可能。家具選びの相談も、いつでもどこでもスムーズに進みます。
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="py-20 px-6">
            <div class="max-w-5xl mx-auto bg-indigo-600 rounded-[3rem] p-12 md:p-20 text-center relative overflow-hidden shadow-2xl shadow-indigo-200">
                <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl"></div>
                <div class="relative z-10 text-white">
                    <h2 class="text-4xl md:text-5xl font-black mb-8">さあ、理想の家づくりを<br>サイズから始めよう。</h2>
                    <a href="{{ route('register') }}" class="inline-block px-12 py-6 bg-white text-indigo-600 font-black rounded-3xl hover:scale-105 transition-all text-xl shadow-xl">
                        無料でアカウントを作成する
                    </a>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-20 border-t border-slate-200">
            <div class="max-w-7xl mx-auto px-6 text-center">
                <div class="flex items-center justify-center gap-2 mb-8">
                    <div class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </div>
                    <span class="text-lg font-black tracking-tighter text-slate-900">HomeSize DB</span>
                </div>
                <div class="flex items-center justify-center gap-6 text-sm font-bold text-slate-400 mb-8">
                    <a href="#" class="hover:text-indigo-600">プライバシーポリシー</a>
                    <a href="#" class="hover:text-indigo-600">利用規約</a>
                    <a href="#" class="hover:text-indigo-600">お問い合わせ</a>
                </div>
                <p class="mt-8 text-sm text-gray-400 font-medium">© {{ date('Y') }} (株)GooDy. All rights reserved.</p>
            </div>
        </footer>
    </body>
</html>
