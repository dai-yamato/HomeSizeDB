# 🏠 住宅寸法管理システム (Home Size Management System)

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php)](https://www.php.net)
[![Livewire](https://img.shields.io/badge/Livewire-v3-FB70A9?style=for-the-badge&logo=livewire)](https://livewire.laravel.com)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-v3-38B2AC?style=for-the-badge&logo=tailwind-css)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

住宅の新築・リフォーム時や家具選びの際に役立つ、家の各部位の寸法（幅・奥行・高さなど）を管理するためのモバイル特化型システムです。家族や施工業者とデータを共有し、いつでもどこでも「あの場所のサイズ」を確認できます。

## ✨ 特徴

### 📱 モバイル特化したドリルダウン UI
*   **直感的な階層構造**: 家 ➔ 階 (1F/2F...) ➔ 部屋 (リビング/キッチン...) ➔ 場所 (クローゼット/窓...) ➔ 計測値。
*   **スマートナビゲーション**: 上部にパンくずリスト。常に現在の階層を把握でき、ワンタップで戻れます。
*   **高速入力**: スマホのテンキーが自動で開く `inputmode="numeric"` 対応。

### 🏘️ マルチテナント & ホーム管理
*   **複数拠点の管理**: 自宅、実家、別荘など複数の「家」を切り替えて管理可能。
*   **データ分離**: Global Scope により、選択中の「家」以外のデータは完全に遮断されます。
*   **権限管理**: Owner, Editor, Viewer のロールに基づいたアクセス制御。

### ✉️ 家族・メンバー招待
*   **署名付きURL招待**: メールアドレスを指定して、安全な招待リンクを発行。
*   **リアルタイム管理**: メンバーリストや招待状況をプロフィール画面から一元管理。

### 📸 視覚的な記録
*   **写真管理**: 各「場所」に写真を登録可能。どの部位のサイズか一目でわかります。

## 🛠 テクノロジー

| Layer | Technology |
| :--- | :--- |
| **Framework** | Laravel 12.x (Latest PHP 8.2+ Syntax) |
| **Frontend** | Livewire v3 (Volt), Alpine.js |
| **Styling** | Tailwind CSS |
| **Database** | PostgreSQL 16/17 |
| **Container** | Docker (PHP-FPM, Nginx, PostgreSQL) |

## 🚀 クイックスタート

### 1. 環境構築
```bash
# リポジトリをクローン
git clone <repository-url>
cd HomeSizeDB/LaravelBase

# Docker コンテナの起動
docker compose up -d --build
```

### 2. 初期設定 (コンテナ内)
```bash
# 依存関係のインストール
docker compose exec app composer install
docker compose exec app npm install

# 環境設定
docker compose exec app php artisan key:generate
docker compose exec app php artisan storage:link

# マイグレーション & デモデータ投入
docker compose exec app php artisan migrate:fresh --seed --seed-class=DemoSeeder
```

### 3. フロントエンドビルド
```bash
docker compose exec app npm run build
```

## 🔐 デモログイン情報
初期データとして以下のユーザーが登録されています。
*   **Email**: `test@example.com`
*   **Password**: `password`

## 📂 データベース構造
*   `homes`: 家の基本情報
*   `floors`: 階層 (1F, 2F等)
*   `rooms`: 部屋 (リビング, 寝室等)
*   `locations`: 具体的な場所 (窓枠, クローゼット内寸等)
*   `measurements`: 計測値 (幅, 高さ, 奥行等)
*   `home_user`: ユーザーと家の所属・権限管理
*   `invitations`: メンバー招待リクエスト

## 📝 ライセンス
本プロジェクトは [MIT ライセンス](LICENSE) の下で公開されています。
