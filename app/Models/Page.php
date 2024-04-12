<?php

namespace App\Models;

use App\Framework\Libs\Model;
use App\Framework\Libs\Database;
use App\Framework\Traits\CachesMarkdown;

class Page extends Model
{
    use CachesMarkdown;

    private $cacheName = "pages";

    /**
     * Store a new page.
     */
    public function add($data) : int
    {
        $success = $this->db->into('pages')->insert([
            'header'        => $data['header'],
            'url'           => $data['url'],
            'title'         => $data['title'],
            'is_public'     => $data['is_public'],
            'content'       => $data['content'],
            'created_at'    => now()
        ])->execute();
        $pageId = $this->db->lastInsertId();

        if ($success)
            $this->updateMarkdownCache($pageId, $data['content']);
        return $pageId;
    }

    /**
     * Save updates to a page.
     */
    public function update(int $pageId, $data) : bool
    {
        // TODO: it is important to validate the incoming
        // data (mass assignment protection)
        $success = $this->db->table('pages')
            ->update($data)
            ->where('id', $pageId)
            ->execute();

        if ($success && array_key_exists('content', $data))
            $this->updateMarkdownCache($pageId, $data['content']);
        return $success;
    }

    /**
     * Show a listing of the pages.
     * 
     * @param bool $includeHidden If true, also non-public pages are included.
     */
    public function list(bool $includeHidden = false) : array
    {
        $query = $this->db->select(['id', 'header', 'title', 'is_public', 'created_at'])->from('pages');
        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Show details of a page.
     * Either a page id or url can be used as a key.
     */
    public function view($pageIdOrUrl, bool $useUrl = false)
    {
        $primaryColumn = $useUrl ? 'url' : 'id';
        $page = $this->db->select(['id', 'header', 'url', 'title', 'is_public', 'content', 'created_at'])
            ->from('pages')
            ->where($primaryColumn, $pageIdOrUrl)
            ->first();

        if (!empty($page))
            $page->contentHtml = $this->getMarkdownCache($page->id);
        return $page;
    }

    /**
     * Return a boolean telling if a page is public or not.
     */
    public function isPublic(int $pageId)
    {
        $page = $this->db->select(['is_public'])
            ->from('pages')
            ->where('pages.id', $pageId)
            ->first();

        if ($page) {
            return $page->is_public;
        } else {
            return false;
        }
    }

    /**
     * Delete a page.
     */
    public function delete(int $pageId)
    {
        $success = $this->db->delete()->from('pages')->where('id', $pageId)->execute();
        $this->deleteMarkdownCache($pageId);
        return $success;
    }
}
