<!-- Bagian 1: Identitas Pelapor -->
<h6 class="mb-3">Identitas Pelapor <span class="fst-italic">(Reporter's Identity)</span></h6>
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <label for="nama_pelapor" class="form-label">Nama Pelapor <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="nama_pelapor" name="nama_pelapor" value="{{ auth()->user()->nama }}"
            readonly required>
    </div>
    <div class="col-md-4 mb-3">
        <label for="jabatan_pelapor" class="form-label">Jabatan <span class="fst-italic">(Position)</span> <span
                class="text-danger">*</span></label>
        <select class="form-select" id="jabatan_pelapor" name="jabatan_pelapor" required>
            <option value="Master/Nakhoda" @if(auth()->user()->jabatan == 'Master/Nakhoda') selected @endif>Master/Nakhoda
            </option>
            <option value="C/O (Chief Officer)" @if(auth()->user()->jabatan == 'C/O (Chief Officer)') selected @endif>C/O
                (Chief Officer)</option>
            <option value="2nd Officer" @if(auth()->user()->jabatan == '2nd Officer') selected @endif>2nd Officer</option>
            <option value="3rd Officer" @if(auth()->user()->jabatan == '3rd Officer') selected @endif>3rd Officer</option>
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label for="telepon_pelapor" class="form-label">Nomor Telepon <span class="fst-italic">(Phone Number)</span>
            <span class="text-danger">*</span></label>
        <input type="tel" class="form-control" id="telepon_pelapor" name="telepon_pelapor"
            value="{{ auth()->user()->phone_number }}" required>
    </div>
</div>

<!-- Bagian 2: Identitas Kapal -->
<h6 class="mb-3">Identitas Kapal <span class="fst-italic">(Ship's Identity)</span></h6>
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <label for="jenis_kapal" class="form-label">Jenis Kapal <span class="fst-italic">(Ship's Type)</span> <span
                class="text-danger">*</span></label>
        <select class="form-select" id="jenis_kapal" name="jenis_kapal" required>
            <option value="KM (Kapal Motor)" @if(auth()->user()->jenis_kapal == 'KM (Kapal Motor)') selected @endif>KM
                (Kapal Motor)</option>
            <option value="MV (Motor Vessel)" @if(auth()->user()->jenis_kapal == 'MV (Motor Vessel)') selected @endif>MV
                (Motor Vessel)</option>
            <option value="MT (Motor Tanker)" @if(auth()->user()->jenis_kapal == 'MT (Motor Tanker)') selected @endif>MT
                (Motor Tanker)</option>
            <option value="SPOB (Self Propeller Oil Barge)" @if(auth()->user()->jenis_kapal == 'SPOB (Self Propeller Oil Barge)') selected @endif>SPOB (Self Propeller Oil Barge)</option>
            <option value="LCT (Landing Craft Tanker)" @if(auth()->user()->jenis_kapal == 'LCT (Landing Craft Tanker)')
            selected @endif>LCT (Landing Craft Tanker)</option>
            <option value="TB (TUG Boat)" @if(auth()->user()->jenis_kapal == 'TB (TUG Boat)') selected @endif>TB (TUG
                Boat)</option>
            <option value="BG (Barge)" @if(auth()->user()->jenis_kapal == 'BG (Barge)') selected @endif>BG (Barge)
            </option>
            <option value="FC (Floating Crane)" @if(auth()->user()->jenis_kapal == 'FC (Floating Crane)') selected @endif>
                FC (Floating Crane)</option>
            <option value="KLM (Kapal Layar Motor / Yacth)" @if(auth()->user()->jenis_kapal == 'KLM (Kapal Layar Motor / Yacth)') selected @endif>KLM (Kapal Layar Motor / Yacth)</option>
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label for="nama_kapal" class="form-label">Nama Kapal <span class="fst-italic">(Ship's Name)</span> <span
                class="text-danger">*</span></label>
        <input type="text" class="form-control" id="nama_kapal" name="nama_kapal" required>
    </div>
    <div class="col-md-4 mb-3" id="kapal-kedua-wrapper" style="display: none;">
        <label for="nama_kapal_kedua" class="form-label">Nama Kapal ke-2 (Gandengan)</label>
        <input type="text" class="form-control" id="nama_kapal_kedua" name="nama_kapal_kedua">
    </div>
    <div class="col-md-4 mb-3">
        <label for="bendera_kapal" class="form-label">Bendera Kapal <span class="fst-italic">(Flag Nationality)</span>
            <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="bendera_kapal" name="bendera_kapal" required>
    </div>
    <div class="col-md-4 mb-3">
        <label for="grt_kapal" class="form-label">Berat Kotor <span class="fst-italic">(GRT)</span> <span
                class="text-danger">*</span></label>
        <input type="number" class="form-control" id="grt_kapal" name="grt_kapal" required>
    </div>
    <div class="col-md-4 mb-3">
        <label for="imo_number" class="form-label">IMO Number / Tanda Selar <span
                class="fst-italic">(Optional)</span></label>
        <input type="text" id="imo_number" name="imo_number" class="form-control">
    </div>
    <div class="col-md-6 mb-3">
        <label for="pelabuhan_asal" class="form-label">Pelabuhan Asal <span class="fst-italic">(Last Port)</span> <span
                class="text-danger">*</span></label>
        <input type="text" class="form-control" id="pelabuhan_asal" name="pelabuhan_asal" required>
    </div>
    <div class="col-md-6 mb-3">
        <label for="waktu_berangkat" class="form-label">Tgl. Berangkat <span class="fst-italic">(Time Departure)</span>
            <span class="text-danger">*</span></label>
        <input type="datetime-local" class="form-control" id="waktu_berangkat" name="waktu_berangkat" required>
    </div>
    <div class="col-md-6 mb-3">
        <label for="pelabuhan_tujuan" class="form-label">Pelabuhan Tujuan <span class="fst-italic">(Port of
                Destination)</span> <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="pelabuhan_tujuan" name="pelabuhan_tujuan" required>
    </div>
    <div class="col-md-6 mb-3">
        <label for="estimasi_tiba" class="form-label">Estimasi Tgl. Tiba <span class="fst-italic">(Est. Time
                Arrival)</span> <span class="text-danger">*</span></label>
        <input type="datetime-local" class="form-control" id="estimasi_tiba" name="estimasi_tiba" required>
    </div>
</div>

<!-- Bagian 3: Kontak & Kepemilikan -->
<h6 class="mb-3">Kontak & Kepemilikan <span class="fst-italic">(Contact & Ownership)</span></h6>
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <label for="pemilik_kapal" class="form-label">Pemilik Kapal <span class="fst-italic">(Ship's Owner)</span> <span
                class="text-danger">*</span></label>
        <input type="text" class="form-control" id="pemilik_kapal" name="pemilik_kapal" required>
    </div>
    <div class="col-md-6 mb-3">
        <label for="kontak_pemilik" class="form-label">Kontak Pemilik Kapal <span class="fst-italic">(Owner's
                Contact)</span> <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="kontak_pemilik" name="kontak_pemilik" required>
    </div>
    <div class="col-md-6 mb-3">
        <label for="agen_lokal" class="form-label">Local Agent <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="agen_lokal" name="agen_lokal" required>
    </div>
    <div class="col-md-6 mb-3">
        <label for="kontak_agen" class="form-label">Kontak Local Agent <span class="fst-italic">(Agent's Contact)</span>
            <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="kontak_agen" name="kontak_agen" required>
    </div>
    <div class="col-md-6 mb-3">
        <label for="nama_pandu" class="form-label">Nama Pilot / Pandu <span class="fst-italic">(Optional)</span></label>
        <input type="text" class="form-control" id="nama_pandu" name="nama_pandu">
    </div>
    <div class="col-md-6 mb-3">
        <label for="nomor_register_pandu" class="form-label">Nomor Register Pandu <span
                class="fst-italic">(Optional)</span></label>
        <input type="text" class="form-control" id="nomor_register_pandu" name="nomor_register_pandu">
    </div>
</div>

<!-- Bagian 4: Data Muatan & Penumpang -->
<h6 class="mb-3">Data Muatan & Penumpang <span class="fst-italic">(Cargo & Passenger Data)</span></h6>
<div class="row">
    <div class="col-md-8 mb-3">
        <label for="jenis_muatan" class="form-label">Jenis Muatan <span class="fst-italic">(Type of Cargo)</span> <span
                class="text-danger">*</span></label>
        <input type="text" class="form-control" id="jenis_muatan" name="jenis_muatan" required>
    </div>
    <div class="col-md-4 mb-3">
        <label for="jumlah_muatan" class="form-label">Jumlah Muatan <span class="fst-italic">(Amount)</span> <span
                class="text-danger">*</span></label>
        <input type="text" class="form-control" id="jumlah_muatan" name="jumlah_muatan" placeholder="Contoh: 100 Ton"
            required>
    </div>
    <div class="col-md-4 mb-3">
        <label for="jumlah_penumpang" class="form-label">Jumlah Penumpang <span class="fst-italic">(Passengers)</span>
            <span class="text-danger">*</span></label>
        <input type="number" class="form-control" id="jumlah_penumpang" name="jumlah_penumpang" value="0" required>
    </div>
</div>