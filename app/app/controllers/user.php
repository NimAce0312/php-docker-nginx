<?php
class User extends Controller
{
    private $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = $this->model('Users');
    }

    public function index()
    {
        $data = $this->db->getUser();

        foreach ($data as $key => $value) {
            $data[$key]->edit = $this->base_url . 'user/edit/' . $value->id;
            $data[$key]->delete = $this->base_url . 'user/delete/' . $value->id;
        }

        $this->view('user/index', $data);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->db->createUser($_POST)) {
                $this->functions->setSessionMessage(true, 'User created successfully');
                $this->functions->redirect('user');
            } else {
                $data = $_POST;
                $this->functions->setSessionMessage(false, 'Failed to create user');
            }
        }

        $this->view('user/create', $data ?? []);
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->db->updateUser($_POST, ['id' => $id])) {
                $this->functions->setSessionMessage(true, 'User updated successfully');
                $this->functions->redirect('user');
            } else {
                $data = (object) $_POST;
                $this->functions->setSessionMessage(false, 'Failed to update user');
            }
        }

        $data = $this->db->getUser(['id' => $id]);
        $this->view('user/edit', $data[0]);
    }

    public function delete($id)
    {
        if ($this->db->deleteUser(['id' => $id])) {
            $this->functions->setSessionMessage(true, 'User deleted successfully');
        } else {
            $this->functions->setSessionMessage(false, 'Failed to delete user');
        }

        $this->functions->redirect('user');
    }
}
