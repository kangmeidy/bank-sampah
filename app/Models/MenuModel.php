<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table = 'tb_menu';
    protected $primaryKey = 'menu_id';
    protected $allowedFields = ['parent_id', 'menu_nama', 'menu_url', 'menu_icon', 'menu_order', 'is_active'];

    public function getMenuItems()
    {
        // Ambil semua menu aktif, urutkan berdasarkan parent_id dan menu_order
        $builder = $this->where('is_active', 1)->orderBy('parent_id', 'ASC')->orderBy('menu_order', 'ASC');
        $menus = $builder->findAll();

        // Buat struktur hirarki (parent-child)
        $menuTree = [];
        foreach ($menus as $menu) {
            $menuTree[$menu['parent_id'] ?? 0][] = $menu;
        }

        return $menuTree;
    }

    // Rekursif membangun HTML menu
    public function buildMenu($menuTree, $parentId = 0)
    {
        if (!isset($menuTree[$parentId])) {
            return '';
        }

        $html = '';
        foreach ($menuTree[$parentId] as $item) {
            $hasChildren = isset($menuTree[$item['menu_id']]);
            $activeClass = (current_url() == base_url($item['menu_url'])) ? 'active' : '';

            if ($hasChildren) {
                $html .= '<li class="nav-item has-treeview">';
                $html .= '<a href="' . base_url($item['menu_url']) . '" class="nav-link ' . $activeClass . '">';
                $html .= '<i class="nav-icon ' . $item['menu_icon'] . '"></i>';
                $html .= '<p>' . $item['menu_nama'] . '<i class="right fas fa-angle-left"></i></p>';
                $html .= '</a>';
                $html .= '<ul class="nav nav-treeview">';
                $html .= $this->buildMenu($menuTree, $item['menu_id']);
                $html .= '</ul>';
                $html .= '</li>';
            } else {
                $html .= '<li class="nav-item">';
                $html .= '<a href="' . base_url($item['menu_url']) . '" class="nav-link ' . $activeClass . '">';
                $html .= '<i class="nav-icon ' . $item['menu_icon'] . '"></i>';
                $html .= '<p>' . $item['menu_nama'] . '</p>';
                $html .= '</a>';
                $html .= '</li>';
            }
        }
        return $html;
    }
}