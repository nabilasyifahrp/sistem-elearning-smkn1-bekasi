@extends('partials.layouts-admin')

@section('content')
    <style>
        .detail-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin: 20px auto;
            width: 100%;
            max-width: 1000px;
            box-sizing: border-box;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .pengumuman-title {
            font-size: 28px;
            font-weight: 700;
            color: #256343;
            margin-bottom: 5px;
        }

        .pengumuman-date {
            font-size: 14px;
            color: #888888;
            margin-bottom: 20px;
        }

        .pengumuman-isi {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .lampiran-link {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 16px;
            flex-wrap: wrap;
        }

        .lampiran-link span {
            font-size: 24px;
        }

        .btn-back {
            background: #256343;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 15px;
        }

        .btn-back:hover {
            background: #1d4c31;
        }

        img.preview {
            max-width: 40%;
            height: auto;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
        }

        @media (max-width: 576px) {
            .pengumuman-title {
                font-size: 22px;
            }

            .pengumuman-isi {
                font-size: 14px;
            }

            .detail-card {
                padding: 20px;
            }

            img.preview {
                max-width: 80%;
            }
        }
    </style>

    <div class="container">
        <a href="{{ route('admin.pengumuman.index') }}" class="btn-back">Kembali</a>

        <div class="detail-card">
            <div class="pengumuman-title">{{ $data->judul }}</div>
            <div class="pengumuman-date">{{ \Carbon\Carbon::parse($data->tanggal_upload)->format('d M Y') }}</div>
            <div class="pengumuman-isi">{!! nl2br(e($data->isi)) !!}</div>

            @if ($data->file_path)
                @php
                    $fileExt = strtolower(pathinfo($data->file_path, PATHINFO_EXTENSION));
                    $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                @endphp

                @if (in_array($fileExt, $imageExts))
                    <img src="{{ asset('storage/' . $data->file_path) }}" alt="Lampiran" class="preview">
                @else
                    <div class="lampiran-link">
                        <span>📄</span>
                        <a href="{{ asset('storage/' . $data->file_path) }}" target="_blank">
                            {{ basename($data->file_path) }}
                        </a>
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection
