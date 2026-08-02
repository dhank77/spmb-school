<?php
/**
 * Script Otomatisasi Laravel Super (Full Auto)
 * 1. Symlink Storage
 * 2. Move Public Files (1 tingkat ke atas)
 * 3. Proteksi .htaccess di folder core
 * 4. Auto-Edit index.php (Update path & PublicPath)
 * 5. Logging proses ke file .txt
 *
 */ 

/// — KONFIGURASI —
$basePath = __DIR__; // Folder 'Core' saat ini
$coreFolderName = basename($basePath); // Nama folder core otomatis (misal: 'Core')
$destinationParent = dirname($basePath, 1); // Folder root (public_html / domain root)
$logFile = $basePath . '/automation_log.txt';
// Fungsi bantuan untuk logging
function writeLog($message, $logFile) {
    $timestamp = date('Y-m-d H:i:s');
    $content = "[$timestamp] $message\n";
    file_put_contents($logFile, $content, FILE_APPEND);
    echo $content;
}
echo "<pre>";writeLog("— MEMULAI PROSES OTOMATISASI —"
, $logFile);

// — 1. PROSES SYMLINK STORAGE —
$targetStorage = $basePath . '/storage/app/public';
$shortcutStorage = $basePath . '/public/storage';
if (!is_link($shortcutStorage)) {
    if (is_dir($targetStorage)) {
        if (symlink($targetStorage, $shortcutStorage)) {
            writeLog("[SUCCESS] Symlink storage dibuat: $shortcutStorage", $logFile);
        } else {
            writeLog("[ERROR] Gagal membuat symlink storage.", $logFile);
        }
    } else {
        writeLog("[ERROR] Folder target storage tidak ditemukan.", $logFile);
    }
} else {
    writeLog("[INFO] Symlink storage sudah ada.", $logFile);
}

// — 2. PINDAHKAN ISI FOLDER PUBLIC KE 1 TINGKAT DI ATAS —
$publicPath = $basePath . '/public';
if (is_dir($publicPath)) {
    $files = scandir($publicPath);
    foreach ($files as $file) {
        if ($file == '.' || $file == '..') continue;
        $source = $publicPath . '/' . $file;
        $dest = $destinationParent . '/' . $file;
        if (rename($source, $dest)) {
            writeLog("[SUCCESS] Pindah: $file -> $destinationParent", $logFile);
        } else {
            writeLog("[ERROR] Gagal memindahkan: $file", $logFile);
        }
    }
} else {
    writeLog("[ERROR] Folder /public tidak ditemukan.", $logFile);
}

// — 3. EDIT INDEX.PHP SECARA OTOMATIS —
$newIndexFile = $destinationParent . '/index.php';
if (file_exists($newIndexFile)) {
    $content = file_get_contents($newIndexFile);
    // Update path autoload & app.php
    // Mencari pattern: __DIR__.'/../
    // Diganti menjadi: __DIR__.'/{nama_folder_core}/
    $content = str_replace("__DIR__.'/../", "__DIR__.'/" . $coreFolderName . "/", $content);
        // Tambahkan usePublicPath(__DIR__) setelah loading app
    // Mencari baris capture request untuk menyisipkan kode sebelumnya
    $publicPathCode = "\n// Set public path ke folder root saat ini\n\$app->usePublicPath(__DIR__);\n\n";
        if (strpos($content, 'usePublicPath') === false) {
        $content = str_replace('$app = require_once', '$app = require_once', $content);
        // Sisipkan sebelum $app->handleRequest
        $content = str_replace('$app->handleRequest', $publicPathCode . '$app->handleRequest', $content);
                if (file_put_contents($newIndexFile, $content)) {
            writeLog("[SUCCESS] index.php telah diperbarui otomatis dengan path '$coreFolderName' dan usePublicPath.", $logFile);
        } else {
            writeLog("[ERROR] Gagal menulis perubahan ke index.php.", $logFile);
        }
    } else {
        writeLog("[INFO] index.php sepertinya sudah memiliki konfigurasi usePublicPath.", $logFile);
    }
} else {
    writeLog("[ERROR] File index.php tidak ditemukan di tujuan pindah.", $logFile);
}

// — 4. BUAT .HTACCESS UNTUK PROTEKSI FOLDER CORE —
$htaccessContent = "Options -Indexes\nDeny from all";
$htaccessFile = $basePath . '/.htaccess';
if (!file_exists($htaccessFile)) {
    if (file_put_contents($htaccessFile, $htaccessContent)) {
        writeLog("[SUCCESS] .htaccess dibuat di folder $coreFolderName untuk proteksi.", $logFile);
    } else {
        writeLog("[ERROR] Gagal membuat .htaccess.", $logFile);
    }
} else {
    writeLog("[INFO] .htaccess sudah ada.", $logFile);
}

// — 5. BUAT SYMLINK LOG KE ROOT (OPSIONAL) —
$logShortcut = $destinationParent . '/install_log.txt';
if (!is_link($logShortcut)) {
    @symlink($logFile, $logShortcut);
    writeLog("[INFO] Log shortcut dibuat di root: install_log.txt", $logFile);
}writeLog("— PROSES SELESAI —"
, $logFile);
echo "</pre>";