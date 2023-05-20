<?php

namespace Plugins\Master\Src;

use Systems\Lib\QueryWrapper;
use Systems\MySQL;

class Suku
{

    protected function db($table)
    {
        return new QueryWrapper($table);
    }

    public function getIndex()
    {

      $totalRecords = $this->mysql('suku_bangsa')
        ->select('id')
        ->toArray();
      $offset         = 10;
      $return['halaman']    = 1;
      $return['jml_halaman']    = ceil(count($totalRecords) / $offset);
      $return['jumlah_data']    = count($totalRecords);

      $return['list'] = $this->mysql('suku_bangsa')
        ->desc('id')
        ->limit(10)
        ->toArray();

      return $return;

    }

    public function anyForm()
    {
        if (isset($_POST['id'])){
          $return['form'] = $this->mysql('suku_bangsa')->where('id', $_POST['id'])->oneArray();
        } else {
          $return['form'] = [
            'id' => '',
            'nama_suku_bangsa' => ''
          ];
        }

        return $return;
    }

    public function anyDisplay()
    {

        $perpage = '10';
        $totalRecords = $this->mysql('suku_bangsa')
          ->select('id')
          ->toArray();
        $offset         = 10;
        $return['halaman']    = 1;
        $return['jml_halaman']    = ceil(count($totalRecords) / $offset);
        $return['jumlah_data']    = count($totalRecords);

        $return['list'] = $this->mysql('suku_bangsa')
          ->desc('id')
          ->offset(0)
          ->limit($perpage)
          ->toArray();

        if(isset($_POST['cari'])) {
          $return['list'] = $this->mysql('suku_bangsa')
            ->like('id', '%'.$_POST['cari'].'%')
            ->orLike('nama_suku_bangsa', '%'.$_POST['cari'].'%')
            ->desc('id')
            ->offset(0)
            ->limit($perpage)
            ->toArray();
          $jumlah_data = count($return['list']);
          $jml_halaman = ceil($jumlah_data / $offset);
        }
        if(isset($_POST['halaman'])){
          $offset     = (($_POST['halaman'] - 1) * $perpage);
          $return['list'] = $this->mysql('suku_bangsa')
            ->desc('id')
            ->offset($offset)
            ->limit($perpage)
            ->toArray();
          $return['halaman'] = $_POST['halaman'];
        }

        return $return;
    }

    public function postSave()
    {
      if (!$this->mysql('suku_bangsa')->where('id', $_POST['id'])->oneArray()) {
        $query = $this->mysql('suku_bangsa')->save($_POST);
      } else {
        $query = $this->mysql('suku_bangsa')->where('id', $_POST['id'])->save($_POST);
      }
      return $query;
    }

    public function postHapus()
    {
      return $this->mysql('suku_bangsa')->where('id', $_POST['id'])->delete();
    }

    protected function mysql($table = NULL)
    {
        return new MySQL($table);
    }

}
