# 住宅寸法管理システム (Home Size Management System)

住宅の新築・リフォーム時や家具選びの際に役立つ、家の各部位の寸法（幅・奥行・高さなど）を管理するためのモバイル特化型システムです。

## 🌟 主な機能

### 1. ドリルダウン形式のUI
モバイルでの操作性を最優先し、画面遷移を直感的に行えるUIを構築しています。
*   **階層構造**: 家 ➔ 階 (1F/2F...) ➔ 部屋 (リビング/キッチン...) ➔ 場所 (クローゼット/窓...) ➔ 計測値
*   **パンくずリスト**: 上部に常に階層を表示し、ワンタップで上位階層に戻れます。

### 2. 計測データの直感的入力
*   **インライン編集**: 1クリックで「幅」「奥行」「高さ」などの入力行を追加可能。
*   **スマホ最適化**: `type="number"` を使用し、入力時にスマホのテンキーが自動で開く設計。
*   **写真管理**: 場所ごとに写真を1枚アップロードし、視覚的に部位を特定できます。

### 3. マルチテナント・招待機能
*   **データ分離**: 「家 (Home)」単位でデータが完全に分離されており、所属していない家のデータにはアクセスできません。
*   **招待システム**: メールアドレスとロール（Owner, Editor, Viewer）を指定して家族や関係者を招待可能です。
*   **権限管理**: 中間テーブル `home_user` による柔軟なリレーション。

## 🛠 技術スタック

*   **Backend**: Laravel 12 (PHP 8.2+)
*   **Frontend**: Livewire v3 (Volt), Tailwind CSS
*   **Database**: PostgreSQL 16/17
*   **Infrastructure**: Docker (PHP-FPM, Nginx, PostgreSQL)
*   **Architectural Patterns**:
    *   Global Scope によるマルチテナント制御
    *   Service パターンによる状態管理 (HomeContext)
    *   PHP 8.2+ の厳格な型定義と最新シンタックス

## 🚀 セットアップ方法

### 前提条件
*   Docker / Docker Compose がインストールされていること

### 手順
1. リポジトリをクローン
2. Dockerコンテナを起動
   ```bash
   docker compose up -d --build
   ```
3. コンテナ内で依存関係のインストールとマイグレーション
   ```bash
   docker compose exec app composer install
   ```
4. アプリケーションキーの生成とマイグレーション
   ```bash
   docker compose exec app php artisan key:generate
   ```
   ```bash
   docker compose exec app php artisan migrate
   ```
5. フロントエンドビルド
   ```bash
   docker compose exec app npm install
   ```
   ```bash
   docker compose exec app npm run build
   ```

## 📂 データベース構造 (ER)

*   **Homes**: 家の基本情報
*   **Floors**: 1F, 2F などの階層
*   **Rooms**: リビング、寝室などの部屋
*   **Locations**: クローゼット、梁、窓枠などの具体的な場所
*   **Measurements**: 幅、高さ、奥行きなどの数値データ
*   **Invitations**: ユーザー招待用のリクエスト管理

## 📝 ライセンス
本プロジェクトは MIT ライセンスの下で公開されています。
