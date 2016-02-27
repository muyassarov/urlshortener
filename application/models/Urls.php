<?php

/**
 * Created by PhpStorm.
 * User: behruz
 * Date: 2/27/16
 * Time: 1:18 PM
 */
class Urls extends CI_Model
{
    public $table = 'urls';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Create short URL
     * @param int $link_length
     * @returns string
     */
    public function create_short_url($link_length = 8)
    {
        $this->load->helper('string');
        $short_url = random_string('alnum', $link_length);
        while ($this->is_short_url_exist($short_url))
        {
            $short_url = random_string('alnum', $link_length);
        }
        return $short_url;
    }

    /**
     * Find long URL before create new one
     * @param $where
     * @return bool
     */
    public function find_long_url($where)
    {
        $this->db->where(array('deleted_at > ' => date('Y-m-d H:i:s')));
        $r = $this->db->select('*')
            ->from($this->table)
            ->where($where)
            ->get();
        if ($r->num_rows())
        {
            return $r->row();
        }
        return FALSE;
    }

    /**
     * Find short URL by short_url string
     * @param $short_url
     * @return bool
     */
    public function find_short_url($short_url)
    {
        $r = $this->db->select('*')
            ->from($this->table)
            ->where(array(
                'url_short'     => $short_url,
                'deleted_at > ' => date('Y-m-d H:i:s')
            ))
            ->get();
        if ($r->num_rows())
        {
            return $r->row();
        }
        return FALSE;
    }

    /**
     * Save URLs pair
     * @param $long_url
     * @param $short_url
     */
    public function save_pair($long_url, $short_url)
    {
        $this->db->insert($this->table, array(
            'url_long'   => $long_url,
            'url_short'  => $short_url,
            'created_at' => date('Y-m-d H:i:s'),
            'deleted_at' => date('Y-m-d H:i:s', strtotime('+15 days')),
            'counter'    => 0
        ));
    }

    /**
     * Increase URL counter
     * @param $id
     * @param $counter
     */
    public function increase_counter($id, $counter)
    {
        $this->db->where('id', $id)
            ->update($this->table, array('counter' => ++$counter));
    }

    /**
     * Check existing long URL
     * @param $long_url
     * @return bool
     */
    private function is_short_url_exist($long_url)
    {
        $r = $this->db->select('id')
            ->from($this->table)
            ->where('url_long', $long_url)
            ->get();
        return ($r->num_rows() > 0);
    }

}