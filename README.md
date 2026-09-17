# Library Web — 圖書館智慧盤點管理系統

`library_Web` 是一套以 **Laravel** 開發的圖書館書籍盤點 Web 管理系統，主要負責圖書館樓層／書櫃配置管理、盤點紀錄瀏覽、影像辨識結果呈現，以及盤點異常的人工確認與修正。

本系統可作為無人機或其他影像擷取／辨識流程的 Web 管理端：外部辨識程式取得指定書櫃資料後，將辨識與盤點結果保存至資料庫，再由管理介面依「盤點批次 → 樓層 → 平面圖 → 書櫃」逐層檢視。

> 此 Repository 主要包含 **Web 後台、資料庫模型、盤點結果顯示與書櫃地圖管理**。影像辨識模型本身不包含在此 Repository 中。

---

## 主要功能

### 1. 圖書館盤點結果管理

- 依盤點時間／批次查看歷史盤點結果。
- 依樓層、平面圖與書櫃逐層查看盤點狀態。
- 顯示辨識影像以及辨識到的書本資訊。
- 記錄書籍是否在架、是否錯位等盤點狀態。
- 保存影像辨識框位置，例如 `x1`、`x2`、`y1`、`y2`。
- 保存辨識書本順序、匹配書籍 ID 與影像序號。
- 支援人工修正辨識／盤點結果。
- 支援依書籍位置編號查詢所在書櫃。

### 2. 圖書館平面圖與書櫃管理

管理員可從 Web 後台維護圖書館實際空間配置：

- 新增、修改與刪除樓層。
- 設定各樓層平面圖尺寸。
- 在平面圖中配置書櫃。
- 設定書櫃位置、寬高與旋轉角度。
- 設定書櫃列數與欄數。
- 設定各書櫃區塊對應的書籍編號範圍。

### 3. 影像辨識系統資料介接

系統提供依書櫃 ID 查詢書籍資料的介面：

```http
GET /API/{bookcaseID}
```

例如：

```http
GET /API/A01
```

Controller 會依 `bookcaseID` 查詢 `virtual_library` 中對應書櫃的書籍資料，供外部影像辨識流程使用。

### 4. 管理後台

網站根目錄會自動導向 Laravel-Admin 後台：

```text
/
└── /admin
```

預設 Admin Route Prefix 為 `admin`，亦可透過環境設定調整。

---

## 系統流程

```mermaid
flowchart LR
    A[無人機 / 相機<br/>取得書櫃影像] --> B[外部影像辨識程式]
    B -->|GET /API/{bookcaseID}| C[Library Web API]
    C --> D[(virtual_library)]
    D --> C
    C --> B

    B -->|盤點與辨識結果| E[(MySQL Database)]

    F[Laravel Web / Laravel-Admin] <--> E
    F --> G[盤點批次]
    G --> H[樓層]
    H --> I[平面圖]
    I --> J[書櫃]
    J --> K[書籍盤點結果 / 影像 / 異常狀態]

    F --> L[Library Map Editor]
    L --> M[樓層 / 書櫃配置]
```

整體概念可簡化為：

```text
影像取得
   ↓
影像辨識
   ↓
書籍匹配 / 盤點結果
   ↓
MySQL
   ↓
Laravel Web 管理後台
   ↓
盤點結果檢視與人工修正
```

---

## 技術架構

| 類別 | 技術 |
| --- | --- |
| Backend | Laravel 10 |
| Language | PHP 8.1+ |
| Admin UI | encore/laravel-admin 1.x |
| Database | MySQL |
| Authentication | Laravel-Admin / Laravel Sanctum |
| Frontend Build Tool | Vite 4 |
| HTTP Client | Axios |
| Dependency Management | Composer / npm |

主要 PHP 套件：

```text
php                     ^8.1
laravel/framework       ^10.10
encore/laravel-admin    1.*
laravel/sanctum         ^3.3
guzzlehttp/guzzle       ^7.2
```

---

## 專案結構

```text
library_Web/
├── app/
│   ├── Admin/
│   │   ├── Controllers/
│   │   │   ├── HomeController.php
│   │   │   ├── InventoryController.php
│   │   │   └── LibraryMapController.php
│   │   └── routes.php
│   │
│   ├── Http/
│   │   └── Controllers/
│   │       └── APIController.php
│   │
│   └── Models/
│       ├── BookCaseNo.php
│       ├── BookInfo.php
│       ├── Floor.php
│       ├── FloorMap.php
│       ├── InventoryBookcaseimg.php
│       ├── InventoryResult.php
│       ├── InventoryTime.php
│       └── Virtual_library.php
│
├── config/
├── database/
│   ├── migrations/
│   └── seeders/
│
├── public/
├── resources/
│   └── views/
│       └── admin/
│           ├── inventory/
│           └── librarymap/
│
├── routes/
│   ├── api.php
│   └── web.php
│
├── storage/
├── tests/
├── .env.example
├── artisan
├── composer.json
├── package.json
└── vite.config.js
```

---

## 主要資料表

### `virtual_library`

提供外部辨識程式查詢指定書櫃中的預期書籍資料。

主要欄位包含：

- `book_shelf`
- `book_name`
- `state`
- `Picture_Url`

### `floors`

保存圖書館樓層與平面圖設計資訊，例如：

- 樓層名稱與排序。
- 設計畫布高度／寬度。
- 備註。

### `floors_maps`

保存書櫃在平面圖上的配置資訊，例如：

- 書櫃名稱。
- `top` / `left` 位置。
- `height` / `width`。
- `rotate` 旋轉角度。
- 書櫃列數／欄數。

### `bookcasenos`

定義每個書櫃區域所對應的書籍編號範圍：

- `ord`
- `startnum`
- `endnum`
- `link_id`

### `books_infos`

保存圖書基本資訊，例如：

- 書名。
- 作者。
- 館藏位置。
- 書籍編號。
- URL。
- 借閱狀態。

### `inventory_times`

保存每一次盤點批次／時間。

### `inventory_results`

保存每本書的辨識與盤點結果，包含：

- 樓層與書櫃位置。
- 在架／不在架／錯位狀態。
- 辨識順序。
- 匹配書籍 ID。
- YOLO / 影像辨識 Bounding Box 座標。
- 辨識影像位置。
- 影像序號。
- 對應盤點批次。

其中原始資料表對 `ishere` 的定義為：

```text
0 = 不在
1 = 在架
2 = 錯位
```

### `inventory_bookcaseimg`

保存每次盤點中，各書櫃所對應的影像資料與影像順序。

### `drone_back`

保存與外部無人機／影像辨識流程相關的資料，例如：

- `book_shelf`
- `predict_picture1`
- `predict_picture2`
- `state`

---

## Web Route 概覽

### 一般 Route

| Method | Route | 用途 |
| --- | --- | --- |
| GET | `/` | 重新導向 `/admin` |
| GET | `/API/{bookcaseID}` | 依書櫃 ID 取得書籍資料 |

### 主要 Admin Route

```text
/admin/inventory
/admin/inventory/{timesname}
/admin/inventory/{timesname}/{floorid}
/admin/inventory/{timesname}/bookcase/{floormapid}
/admin/inventory/end/...
/admin/inventory/small/...

/admin/librarymap/floor
/admin/librarymap/floor/edit
/admin/librarymap/floormap/{floorid}
/admin/librarymap/floormap/edit/{floorid}
/admin/librarymap/bookcase/{floormapid}
```

> 實際 Admin Prefix 可由 `ADMIN_ROUTE_PREFIX` 修改，因此以上路徑以預設 `/admin` 為例。

---

# 安裝與執行

## 1. 環境需求

建議先安裝：

- PHP 8.1 或更新版本
- Composer
- MySQL
- Node.js
- npm
- Git

---

## 2. Clone Repository

```bash
git clone https://github.com/LiaoZike/library_Web.git
cd library_Web
```

---

## 3. 安裝 PHP 套件

```bash
composer install
```

---

## 4. 建立環境設定

Linux / macOS：

```bash
cp .env.example .env
```

Windows CMD：

```bat
copy .env.example .env
```

接著產生 Laravel Application Key：

```bash
php artisan key:generate
```

---

## 5. 設定資料庫

先在 MySQL 建立資料庫，例如：

```sql
CREATE DATABASE library_web
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

修改 `.env`：

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=library_web
DB_USERNAME=root
DB_PASSWORD=your_password
```

然後執行 Migration：

```bash
php artisan migrate
```

> 如果你是從原本已部署的系統搬移專案，通常還需要匯入原本的圖書資料、樓層配置與盤點資料庫內容。

---

## 6. Laravel-Admin 管理員帳號

此 Repository 的 `DatabaseSeeder` 目前沒有建立預設管理員帳號。

因此若使用的是**全新資料庫**，除了執行 Migration 外，還需要另外建立 Laravel-Admin 管理員／角色／權限資料，或匯入既有系統的 Admin 資料表。

如果使用既有正式資料庫，請直接使用原本的管理員帳號資料即可。

> 不建議將正式管理員帳號或密碼直接寫入 Repository。

---

## 7. 安裝前端套件

```bash
npm install
```

開發環境：

```bash
npm run dev
```

正式 Build：

```bash
npm run build
```

---

## 8. 啟動 Laravel

```bash
php artisan serve
```

預設網址：

```text
http://127.0.0.1:8000
```

進入首頁後會導向：

```text
http://127.0.0.1:8000/admin
```

開發時通常需要同時執行：

```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```

---

# API 使用方式

## 查詢指定書櫃

```http
GET /API/{bookcaseID}
```

例如：

```bash
curl http://127.0.0.1:8000/API/A01
```

此 API 會查詢：

```text
virtual_library.book_shelf = {bookcaseID}
```

並回傳該書櫃所對應的書籍資料。

因此影像辨識端可以使用書櫃 ID 取得該區域的候選／預期書籍，再執行後續辨識與匹配。

---

# 盤點資料階層

Web 管理介面中的盤點資料大致依照以下階層組織：

```text
Inventory Time
    ↓
Floor
    ↓
Floor Map
    ↓
Bookcase
    ↓
Book
    ↓
Inventory Result
```

也就是先選擇某次盤點，再逐步進入樓層、平面圖與書櫃，最後查看單本書的盤點結果。

---

## Database

若只執行 Migration，會建立資料表結構，但不會自動產生實際館藏資料與完整管理員資料。

若要在另一台電腦完整還原系統，通常需要同時準備：

```text
1. Source Code
2. .env
3. MySQL Database Dump
4. 使用中的影像／上傳檔案
5. 外部影像辨識程式與其環境
```

