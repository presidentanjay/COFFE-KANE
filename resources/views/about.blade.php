@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-6 text-white">
    <h1 class="text-4xl font-extrabold text-center text-pink-400 mb-2">ABOUT</h1>
    <p class="text-center italic text-gray-300 mb-8">Offering Extra Special Service</p>

    {{-- Gambar Full Width di Atas --}}
    <div class="w-full mb-10">
        <img src="https://images.unsplash.com/photo-1541167760496-1628856ab772?auto=format&fit=crop&w=1200&q=80"
             alt="Coffee Kane Latte Art"
             class="rounded-xl shadow-xl border border-gray-700 w-full h-auto object-cover">
    </div>

    {{-- Konten Tulisan --}}
    <div class="space-y-6 text-gray-300 leading-relaxed text-justify">
        <p>
            Kami berkomitmen penuh untuk menyajikan kopi terbaik yang mampu membangkitkan kenangan dan kehangatan bagi para penikmat kopi sejati.
            Setiap cangkir yang kami sajikan tidak sekadar minuman, melainkan hasil dari proses yang teliti dan penuh cinta. Dimulai dari pemilihan
            biji kopi berkualitas tinggi dari petani lokal terbaik, proses sangrai dilakukan dengan teknik khusus untuk menjaga cita rasa asli dan
            menghasilkan aroma yang khas. Kami percaya bahwa kualitas terbaik berasal dari perhatian terhadap detail kecil yang sering kali tidak
            terlihat oleh mata, namun terasa oleh hati.
        </p>

        <p>
            Di Coffee Kane, setiap barista kami adalah seorang seniman yang menguasai teknik penyeduhan secara mendalam. Mereka tidak hanya menyeduh,
            tetapi juga menciptakan pengalaman rasa melalui seni latte dan cara penyajian yang artistik. Sentuhan personal dalam setiap penyajian
            membuat setiap kunjungan menjadi momen yang bermakna. Selain kopi, kami juga menghadirkan pilihan makanan ringan yang menggoda selera,
            seperti roti panggang gurih, kue homemade, dan aneka pastry khas rumahan yang selalu segar setiap hari.
        </p>

        <p>
            <strong>Coffee Kane</strong> mulai berdiri sejak tahun <strong>2019 di Sumedang, Jawa Barat</strong>, dengan visi menjadi coffee shop yang
            tidak hanya menjual produk, tetapi juga menghadirkan rasa nyaman dan kedekatan. Kami bangga menggunakan <strong>bahan baku lokal berkualitas</strong>
            yang bersumber dari daerah penghasil kopi ternama di Indonesia seperti <em>Gayo</em>, <em>Toraja</em>, dan <em>Ciwidey</em>. Hal ini merupakan bentuk
            dukungan kami terhadap petani lokal dan komitmen kami untuk mempertahankan kualitas rasa khas nusantara.
        </p>

        <p>
            Kami percaya bahwa suasana sangat berperan penting dalam menciptakan pengalaman yang berkesan. Oleh karena itu, Coffee Kane dirancang dengan
            nuansa hangat dan kekeluargaan, agar setiap pengunjung merasa seperti di rumah sendiri. Tim kami yang profesional dan ramah siap membantu Anda
            memilih sajian terbaik sesuai selera dan memberikan rekomendasi yang akan memperkaya pengalaman ngopi Anda.
        </p>
    </div>
</div>
@endsection
