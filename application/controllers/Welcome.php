<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *        http://example.com/index.php/welcome
     *    - or -
     *        http://example.com/index.php/welcome/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        $this->load->view('common/header');
        $this->load->view('create/form');
        $this->load->view('common/footer');
    }

    /**
     * Ajax check long url accessibility
     * @return string JSON object
     */
    public function ajax_validate_long_url()
    {
        $url = $this->input->get('url');
        $this->validate_long_url($url);
        if (!$this->validate_long_url_access($url))
        {
            $this->send_json(array(
                'error'   => TRUE,
                'message' => 'Entered URL is not accessible'
            ));
        }
        else
        {
            $this->send_json(array(
                'success' => TRUE
            ));
        }
    }

    /**
     * Create short URL
     */
    public function ajax_create_short_url()
    {
        $url         = $this->input->post('url');
        $desired_url = $this->input->post('desired_url');
        $this->validate_long_url($url);
        $this->load->model('Urls');

        $result = $this->Urls->find_long_url(array(
            'url_long' => $url
        ));
        if (is_object($result))
        {
            $this->send_json(array(
                'short_url' => base_url() . $result->url_short,
                'message'   => '<strong>Notification!</strong> This URL already exists in database, please ' .
                    'use existing link',
                'success'   => TRUE
            ));
        }
        if ($desired_url)
        {
            if ($this->validate_desired_url($desired_url))
            {
                $this->send_json(array(
                    'error'   => TRUE,
                    'message' => 'Desired URL is not valid, please check your data'
                ));
            }
            if ($this->Urls->find_short_url($desired_url))
            {
                $this->send_json(array(
                    'error'   => TRUE,
                    'message' => 'Desired URL already exists, please enter another short URL'
                ));
            }
            $short_url = $desired_url;
        }
        else
        {
            $this->load->config('urlshortener');
            $short_url = $this->Urls->create_short_url($this->config->item('urlshortener_length'));
        }

        $this->Urls->save_pair($url, $short_url);
        $this->send_json(array(
            'short_url' => base_url() . $short_url,
            'message'   => '<strong>Success!</strong> Short URL was successfully created',
            'success'   => TRUE
        ));
    }

    /**
     * Validate desired URL string
     * @param $url
     * @return bool
     */
    private function validate_desired_url($url)
    {
        if (preg_match('/[^a-z_\-0-9]/i', $url))
        {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Validate long URL format
     * @param $url
     * @return bool
     */
    private function validate_long_url($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === FALSE)
        {
            $this->send_json(array(
                'error'   => TRUE,
                'message' => 'Please check your entered URL, it is not valid URL'
            ));
        }
        return TRUE;
    }

    /**
     * Validate long URL accessibility
     * @param $url
     * @return bool
     */
    private function validate_long_url_access($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOBODY, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_exec($ch);
        $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return (!empty($response) && $response != 404);
    }

    /**
     * Send json data to the browser
     * @param $data
     */
    private function send_json($data)
    {
        $this->db->close();
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($data));
        echo $this->output->get_output();
        exit();
    }
}
