<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use CodeIgniter\HTTP\ResponseInterface;

class CategoryController extends BaseController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $categories = $this->categoryModel->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $categories
        ]);
    }

    public function create()
    {
        $data = $this->request->getJSON(true);

        $rules = [
            'name' => 'required|min_length[3]',
            'slug' => 'required|is_unique[categories.slug]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Validation failed',
                'errors'  => $this->validator->getErrors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $categoryId = $this->categoryModel->insert($data);
        $category = $this->categoryModel->find($categoryId);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Category created successfully',
            'data'    => $category
        ])->setStatusCode(ResponseInterface::HTTP_CREATED);
    }

    public function update($id)
    {
        $category = $this->categoryModel->find($id);

        if (!$category) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Category not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $data = $this->request->getJSON(true);
        $this->categoryModel->update($id, $data);

        $updatedCategory = $this->categoryModel->find($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Category updated successfully',
            'data'    => $updatedCategory
        ]);
    }

    public function delete($id)
    {
        $category = $this->categoryModel->find($id);

        if (!$category) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Category not found'
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $this->categoryModel->delete($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Category deleted successfully'
        ]);
    }
}
