<?php

namespace App\Livewire\Admin\Books;

use App\Models\Buku;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $showModal = false;
    public $bukuId = null;
    public $judul = '';
    public $penulis = '';
    public $isbn = '';
    public $kategori = '';
    public $deskripsi = '';
    public $cover_img = null;
    public $temp_cover_img = null;
    public $stok = 0;
    public $denda_harian = 0;
    public $penerbit = '';
    public $tahun_terbit = '';
    
    public $showSuccessNotification = false;
    public $notificationMessage = '';

    // Protect properties from unintended Livewire resets
    protected $listeners = ['refreshBooks' => '$refresh'];

    protected function rules()
    {
        $rules = [
            'judul' => 'required|string',
            'penulis' => 'required|string',
            'isbn' => 'nullable|string',
            'kategori' => 'required|in:al-quran,hadis,fikih,akidah,sirah,tafsir,tarbiyah,sejarah,buku-anak,novel,lainnya',
            'deskripsi' => 'nullable|string',
            'cover_img' => 'nullable|image|max:1024',
            'stok' => 'required|integer|min:0',
            'denda_harian' => 'required|integer|min:0',
            'penerbit' => 'nullable|string',
            'tahun_terbit' => 'nullable|integer'
        ];

        // Only add the unique validation for ISBN when creating a new book or changing the ISBN
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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        // Reset semua field saat membuat buku baru
        $this->reset(['bukuId', 'judul', 'penulis', 'isbn', 'kategori', 'deskripsi', 
                     'cover_img', 'temp_cover_img', 'stok', 'denda_harian', 'penerbit', 'tahun_terbit']);
        $this->showModal = true;
    }

    public function edit($bukuId)
    {
        $this->resetValidation();
        $this->reset(['cover_img']); // Reset cover_img agar tidak terjadi validasi error
        
        $buku = Buku::findOrFail($bukuId);
        
        // Set property values directly
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
        
        // Force Livewire to recognize the data update
        $this->dispatch('propertyUpdated');
        
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

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

        if ($this->cover_img) {
            $path = $this->cover_img->store('covers', 'public');
            $data['cover_img'] = $path;
        }

        if ($this->bukuId) {
            $buku = Buku::find($this->bukuId);
            if ($this->cover_img && $buku->cover_img) {
                Storage::disk('public')->delete($buku->cover_img);
            }
            $buku->update($data);
            $this->notificationMessage = 'Buku berhasil diperbarui!';
        } else {
            Buku::create($data);
            $this->notificationMessage = 'Buku berhasil ditambahkan!';
        }

        // Show the notification
        $this->showSuccessNotification = true;
        
        // Dispatch event to auto-hide notification after 3 seconds
        $this->dispatch('hideSuccessNotification');

        $this->showModal = false;
        $this->reset(['judul', 'penulis', 'isbn', 'kategori', 'deskripsi', 
                     'cover_img', 'temp_cover_img', 'stok', 'denda_harian', 'penerbit', 'tahun_terbit', 'bukuId']);
    }

    public function delete($bukuId)
    {
        $buku = Buku::find($bukuId);
        if ($buku->cover_img) {
            Storage::disk('public')->delete($buku->cover_img);
        }
        $buku->delete();
        
        // Show deletion notification
        $this->notificationMessage = 'Buku berhasil dihapus!';
        $this->showSuccessNotification = true;
        
        // Dispatch event to auto-hide notification after 3 seconds
        $this->dispatch('hideSuccessNotification');
    }

    public function render()
    {
        $books = Buku::where('judul', 'like', '%'.$this->search.'%')
                    ->orWhere('penulis', 'like', '%'.$this->search.'%')
                    ->orWhere('isbn', 'like', '%'.$this->search.'%')
                    ->paginate(10);

        return view('livewire.admin.books.index', compact('books'))->layout('layouts.admin');
    }
}