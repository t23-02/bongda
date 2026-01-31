<?php
// Danh sách các link
$linkMap = [
    1 => 'https://tctv.pro',
    2 => 'https://vbtv.pro',
    3 => 'https://cktv.pro',
    4 => 'https://tt.8share.pro/chuoichien',
    5 => 'https://tt.8share.pro/vachvoi',
    6 => 'https://tt.8share.pro/luongson',
    7 => 'https://tt.8share.pro/buncha',
    8 => 'https://tt.8share.pro/khandaia',
    9 => 'https://tt.8share.pro/streamed',
    10 => 'https://tt.8share.pro/gavang',
    11 => 'https://tt.8share.pro/hoiquan',
    12 => 'https://tt.8share.pro/socolive',
    13 => 'https://tt.8share.pro/hoadao',
    14 => 'https://op.hdpe.pro',
    15 => 'https://kk.hdpe.pro',
    16 => 'https://nc.hdpe.pro',
    17 => 'https://iptv.animehay.dev',
    18 => 'https://iptv.anime47.net',
    19 => 'https://iptv.nhadai.org/v1',
    20 => 'https://vtvgo.4share.me',
    21 => 'https://ht.khotruyen.link',
    22 => 'https://otruyen-iptv.khotruyen.link',
    23 => 'https://newtruyenhot.4share.me',
    24 => 'https://xxxapi.xyz/xxvn',
    25 => 'https://xxxapi.xyz/vlxx',
    26 => 'https://xxxapi.xyz/sextop1',
    27 => 'https://xxxapi.xyz/heovl',
    28 => 'https://truyenx.link/truyensextv',
    29 => 'https://truyenx.link/sayhentai',
    30 => 'https://mg18fx.truyenx.link'
];

// Danh sách các ID cần loại bỏ
$removeIds = ['related-providers', 'related'];

// Lấy ID từ URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Kiểm tra ID hợp lệ
if ($id === 0 || !isset($linkMap[$id])) {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code($id === 0 ? 200 : 400);
    echo json_encode([
        'error' => $id === 0 ? null : 'ID không hợp lệ',
        'message' => 'Sử dụng: ?id=[1-30]',
        'removed_groups' => $removeIds,
        'sources' => array_keys($linkMap)
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit;
}

// Lấy URL và nội dung
$targetUrl = $linkMap[$id];
$jsonContent = @file_get_contents($targetUrl);

if ($jsonContent === false) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'error' => 'Không thể lấy dữ liệu từ nguồn',
        'url' => $targetUrl
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

// Decode và xử lý JSON
$data = json_decode($jsonContent, true);

if ($data === null) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'error' => 'Dữ liệu JSON không hợp lệ'
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

// Loại bỏ các group không mong muốn
if (isset($data['groups']) && is_array($data['groups'])) {
    $data['groups'] = array_values(array_filter($data['groups'], function($group) use ($removeIds) {
        return !isset($group['id']) || !in_array($group['id'], $removeIds);
    }));
}

// Trả về kết quả
header('Content-Type: application/json; charset=utf-8');
header('X-Source-URL: ' . $targetUrl);
header('X-Removed-Groups: ' . implode(', ', $removeIds));
echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?>
```

**Cách sử dụng:**

1. **Lấy dữ liệu đã xử lý:**
```
   http://127.0.0.1/list?id=1
   http://127.0.0.1/list?id=19
```

2. **Xem danh sách nguồn:**
```
   http://127.0.0.1/list
```

3. **Bỏ qua cache:**
```
   http://127.0.0.1/list?id=1&nocache=1
```

4. **Xóa toàn bộ cache:**
```
   http://127.0.0.1/list?clearall=1