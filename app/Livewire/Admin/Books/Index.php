<?php

// Namespace untuk menentukan lokasi class dalam aplikasi
namespace App\Livewire\Admin\Books;

// Mengimpor model Buku untuk berinteraksi dengan database
use App\Models\Buku;
// Mengimpor fitur Livewire untuk pembuatan komponen interaktif
use Livewire\Component;
// Mengimpor fitur paginasi agar data buku bisa ditampilkan dalam beberapa halaman
use Livewire\WithPagination;
// Mengimpor fitur untuk mengunggah file (gambar cover buku)
use Livewire\WithFileUploads;
// Mengimpor Storage untuk mengelola file yang diunggah
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    // Menggunakan trait Livewire untuk fitur paginasi dan unggah file
    use WithPagination, WithFileUploads;

    // Variabel untuk menyimpan data input dari form pengguna
    public $search = ''; // Input untuk pencarian buku
    public $showModal = false; // Menentukan apakah modal tambah/edit buku ditampilkan
    public $bukuId = null; // Menyimpan ID buku yang sedang diedit
    public $judul = ''; // Judul buku
    public $penulis = ''; // Nama penulis buku
    public $isbn = ''; // ISBN (nomor unik buku)
    public $kategori = ''; // Kategori buku
    public $deskripsi = ''; // Deskripsi buku
    public $cover_img = null; // Menyimpan gambar cover baru yang diunggah
    public $temp_cover_img = null; // Menyimpan gambar cover lama sebelum diganti
    public $stok = 0; // Jumlah stok buku
    public $denda_harian = 0; // Biaya denda per hari jika buku telat dikembalikan
    public $penerbit = ''; // Nama penerbit buku
    public $tahun_terbit = ''; // Tahun terbit buku

    // Variabel untuk menampilkan notifikasi sukses setelah operasi berhasil
    public $showSuccessNotification = false;
    public $notificationMessage = '';

    // Listener untuk menangani event pembaruan data buku
    protected $listeners = ['refreshBooks' => '$refresh'];

    /**
     * Aturan validasi untuk input form
     */
    protected function rules()
    {
        $rules = [
            'judul' => 'required|string', // Judul harus diisi dan berupa string
            'penulis' => 'required|string', // Nama penulis wajib diisi
            'isbn' => 'nullable|string', // ISBN bersifat opsional
            'kategori' => 'required|in:al-quran,hadis,fikih,akidah,sirah,tafsir,tarbiyah,sejarah,buku-anak,novel,lainnya',
            'deskripsi' => 'nullable|string', // Deskripsi bersifat opsional
            'cover_img' => 'nullable|image|max:1024', // Gambar opsional, maksimal 1MB
            'stok' => 'required|integer|min:0', // Stok wajib angka dan minimal 0
            'denda_harian' => 'required|integer|min:0', // Denda wajib angka dan minimal 0
            'penerbit' => 'nullable|string', // Nama penerbit opsional
            'tahun_terbit' => 'nullable|integer' // Tahun terbit bersifat opsional
        ];

        // Validasi ISBN harus unik jika menambah buku baru atau mengubah ISBN
        if (!$this->bukuId) {
            $rules['isbn'] = 'nullable|string|unique:bukus,isbn';
        } else {
            $buku = Buku::find($this->bukuId);
            if ($buku && $this->isbn !== $buku->isbn) {
                $rules['isbn'] = 'nullable|string|unique:bukus,isbn';
            }
        }

        return $rules;
    }

    /**
     * Reset halaman paginasi saat pencarian berubah
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Menampilkan modal untuk menambahkan buku baru
     */
    public function create()
    {
        // Reset semua field sebelum menambah buku baru
        $this->reset([
            'bukuId', 'judul', 'penulis', 'isbn', 'kategori', 'deskripsi', 
            'cover_img', 'temp_cover_img', 'stok', 'denda_harian', 'penerbit', 'tahun_terbit'
        ]);
        $this->showModal = true;
    }

    /**
     * Mengisi form dengan data buku yang akan diedit
     */
    public function edit($bukuId)
    {
        $this->resetValidation();
        $this->reset(['cover_img']); // Reset cover_img untuk menghindari validasi error

        $buku = Buku::findOrFail($bukuId);
        
        // Mengisi variabel dengan data buku yang dipilih
        $this->bukuId = $bukuId;
        $this->judul = $buku->judul;
        $this->penulis = $buku->penulis;
        $this->isbn = $buku->isbn;
        $this->kategori = $buku->kategori;
        $this->deskripsi = $buku->deskripsi;
        $this->temp_cover_img = $buku->cover_img;
        $this->stok = $buku->stok;
        $this->denda_harian = $buku->denda_harian;
        $this->penerbit = $buku->penerbit;
        $this->tahun_terbit = $buku->tahun_terbit;

        $this->showModal = true;
    }

    /**
     * Menyimpan data buku baru atau memperbarui buku yang ada
     */
    public function save()
    {
        $this->validate(); // Validasi input sebelum menyimpan

        // Menyiapkan data untuk disimpan
        $data = [
            'judul' => $this->judul,
            'penulis' => $this->penulis,
            'isbn' => $this->isbn,
            'kategori' => $this->kategori,
            'deskripsi' => $this->deskripsi,
            'stok' => $this->stok,
            'denda_harian' => $this->denda_harian,
            'penerbit' => $this->penerbit,
            'tahun_terbit' => $this->tahun_terbit,
        ];

       // Jika ada cover baru, simpan ke penyimpanan
       if ($this->cover_img) {
        $path = $this->cover_img->store('covers', 'public');
        $data['cover_img'] = $path;
    }

    if ($this->bukuId) {
        // Update buku jika sedang dalam mode edit
        $buku = Buku::find($this->bukuId);
        if ($this->cover_img && $buku->cover_img) {
            Storage::disk('public')->delete($buku->cover_img);
        }
        $buku->update($data);
        $this->notificationMessage = 'Buku berhasil diperbarui!';
    } else {
        // Tambah buku baru
        Buku::create($data);
        $this->notificationMessage = 'Buku berhasil ditambahkan!';
    }

    // Tampilkan notifikasi sukses
    $this->showSuccessNotification = true;
    $this->dispatch('hideSuccessNotification'); // Auto-hide notifikasi setelah 3 detik

    // Tutup modal dan reset form
    $this->showModal = false;
    $this->reset([
        'judul', 'penulis', 'isbn', 'kategori', 'deskripsi', 
        'cover_img', 'temp_cover_img', 'stok', 'denda_harian', 'penerbit', 'tahun_terbit', 'bukuId'
    ]);
}

/**
 * Menghapus buku dari database
 */
public function delete($bukuId)
{
    $buku = Buku::find($bukuId);
    if ($buku->cover_img) {
        Storage::disk('public')->delete($buku->cover_img); // Hapus cover jika ada
    }
    $buku->delete(); // Hapus buku dari database

    // Tampilkan notifikasi sukses
    $this->notificationMessage = 'Buku berhasil dihapus!';
    $this->showSuccessNotification = true;
    $this->dispatch('hideSuccessNotification');
}

/**
 * Menampilkan daftar buku dengan paginasi
 */
public function render()
{
    // Query buku berdasarkan pencarian judul, penulis, atau ISBN
    $books = Buku::where('judul', 'like', '%'.$this->search.'%')
                ->orWhere('penulis', 'like', '%'.$this->search.'%')
                ->orWhere('isbn', 'like', '%'.$this->search.'%')
                ->paginate(10);

    // Mengembalikan tampilan Livewire dengan daftar buku
    return view('livewire.admin.books.index', compact('books'))->layout('layouts.admin');
}
}
