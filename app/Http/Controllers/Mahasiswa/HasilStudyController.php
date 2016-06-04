<?php
/**
 * Ini adalah controller untuk penampilan dan juga perhitungan Hasil Study Mahasiswa!.
 * Hasil study yang tampil di sini adalah milik Mahasiswa yang login.
 * User: toni
 * Date: 12/05/16
 * Time: 17:07
 */

namespace Stmik\Http\Controllers\Mahasiswa;


use Stmik\Factories\HasilStudyMahasiswaFactory;
use Stmik\Http\Controllers\Controller;
use Stmik\Http\Controllers\GetDataBTTableTrait;
// nyontek dari IsiFRSController (banghaji)
use Stmik\Factories\MahasiswaFactory;

class HasilStudyController extends Controller
{
    use GetDataBTTableTrait;

    protected $factory;

    public function __construct(HasilStudyMahasiswaFactory $factory)
    {
        $this->factory = $factory;
        $this->authorize('dataIniHanyaBisaDipakaiOleh', 'mahasiswa');
    }

    public function index()
    {
        return view('mahasiswa.hasil-study.index')
            ->with('data', $this->factory->getDataMahasiswa())
            ->with('layout', $this->getLayout());
    }

    /**
     * Tampilkan IPS untuk mahasiswa terpilih, namun apabila nilai param $nim tidak ada berarti yang login saat itu yang
     * mengaksesnya. Di load untuk ajax saja!
     * @param null $nim
     */
    public function ips($nim = null)
    {
        $nim = ($nim === null? \Session::get('username', 'NOTHING') : $nim);

        return view('mahasiswa.hasil-study.ips')
            ->with('data', $this->factory->loadDataPerhitunganIPS($nim));
    }

    /**
     * Tampilkan perhitungan IPK, bila NIM tidak terpilih tampilkan yang saat itu aktif saja!
     * @param null $nim
     */
    public function ipk($nim = null)
    {
        // baca data mhs ybs (banghaji)
		$mhsFactory = new MahasiswaFactory();
        $nim = $mhsFactory->getNIM($nim);
        $mhs = $mhsFactory->getDataMahasiswa($nim);

        // kirim data ke view (banghaji)
		return view('mahasiswa.hasil-study.ipk')
            ->with('mhs', $mhs)
            ->with('data', $this->factory->loadDataHasilStudy($nim));
    }


}