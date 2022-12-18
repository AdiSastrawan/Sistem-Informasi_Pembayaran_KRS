<div class="container-fluid">
  <!-- Start Btn Buat Form Bukti -->
  <div>
    <button type="button" class="btn btn-info ">
      <a class="text-decoration-none text-white font-weight-bolder" href="<?= base_url('krs/buat_iuran') ?>">
        <span class="pr-2"><i class="fas fa-plus"></i></span>
        Tambah Iuran
      </a>
    </button>
  </div>
  <!-- End Btn Buat Form Bukti -->

  <!-- Start  Table -->
  <div class="row my-4">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-body px-0 pt-4 pb-2">
          <div class="table-responsive">
            <table class="table align-items-center text-center">
              <thead>
                <tr class="fw-bold">
                  <td class="col-1">No</td>
                  <td class="col-2">Tahun Ajaran</td>
                  <td class="col-2">Semester</td>
                  <td class="col-2">Aktif</td>
                  <td class="col-2">Bukti Pembayaran Iuran</td>
                </tr>
              </thead>
              <tbody>
                <?php $i = 1 ?>
                <?php foreach ($iuran as $bukti) : ?>
                  <tr>
                    <td>
                      <?php if ($i > 9) : ?>
                        <p><?= $i++ ?></p>
                      <?php else : ?>
                        <p>0<?= $i++ ?></p>
                      <?php endif; ?>
                    </td>

                    <td>
                      <div>
                        <p><?= $bukti['tahun_ajaran'] ?>/ <?= $bukti['tahun_ajaran'] + 1 ?></p>
                      </div>
                    </td>
                    <td>
                      <div>
                        <p><?= $bukti['semester'] ?></p>
                      </div>
                    </td>
                    <td>
                      <button class="btn btn-sm btn-success">Active</button>
                    </td>
                    <td>
                      <div>
                        <button type="button" class="btn btn-primary"><a class="text-decoration-none text-white font-weight-bolder" href="<?= base_url() ?>krs/viewBukti/<?php echo $bukti['id'] ?>">Lihat <i class="far fa-eye"></i></a>

                        </button>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End Table -->
</div>