<?php
class Post extends Model {

    /** Listar posts (opcional: por categoría por nombre exacto) */
    public function all(?string $categoryName = null): array {
        $sql = "SELECT p.id, p.title, p.slug, p.summary, p.created_at, p.published_at,
                       c.name AS category, pt.name AS type, p.content_json
                FROM posts p
                JOIN categories c ON c.id = p.category_id
                JOIN post_types pt ON pt.id = p.type_id
                WHERE p.status = 'published'";

        $params = [];
        if ($categoryName) {
            $sql .= " AND LOWER(c.name) = LOWER(:cat)";
            $params[':cat'] = $categoryName;
        }
        $sql .= " ORDER BY COALESCE(p.published_at, p.created_at) DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll() ?: [];
    }

    /** Obtener post por id */
    public function find(string $id): ?array {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name AS category, pt.name AS type
             FROM posts p
             JOIN categories c ON c.id = p.category_id
             JOIN post_types pt ON pt.id = p.type_id
             WHERE p.id = :id"
        );
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /** Crear post a partir del payload del formulario */
    public function create(array $payload): array {
        $title    = trim($payload['title'] ?? 'Sin título');
        $summary  = trim($payload['summary'] ?? '');
        $catName  = trim($payload['category'] ?? 'General');
        $typeName = trim($payload['type'] ?? 'article');
        $json     = $payload['content'] ?? '';

        // Resolver IDs (crea la categoría si no existe)
        $categoryId = $this->resolveCategory($catName);
        $typeId     = $this->resolveType($typeName);

        // slug único
        $slug = $this->uniqueSlug($this->slugify($title));

        // Normalizar JSON si es texto
        $contentJson = null;
        if ($json !== '') {
            $decoded = json_decode($json, true);
            $contentJson = (json_last_error() === JSON_ERROR_NONE)
                ? json_encode($decoded, JSON_UNESCAPED_UNICODE)
                : json_encode(['text' => $json], JSON_UNESCAPED_UNICODE);
        }

        $stmt = $this->db->prepare(
            "INSERT INTO posts (author_id, category_id, type_id, title, slug, summary, content_json, status, published_at)
             VALUES (NULL, :cat, :type, :title, :slug, :summary, :json, 'published', NOW())"
        );
        $stmt->execute([
            ':cat' => $categoryId,
            ':type' => $typeId,
            ':title' => $title,
            ':slug' => $slug,
            ':summary' => $summary,
            ':json' => $contentJson
        ]);

        $id = (string)$this->db->lastInsertId();
        return $this->find($id);
    }

    /* ---------- helpers ---------- */

    private function resolveCategory(string $name): int {
        // Buscar
        $s = $this->db->prepare("SELECT id FROM categories WHERE LOWER(name)=LOWER(:n) LIMIT 1");
        $s->execute([':n' => $name]);
        $id = $s->fetchColumn();
        if ($id) return (int)$id;

        // Crear si no existe
        $ins = $this->db->prepare("INSERT INTO categories (name, slug) VALUES (:n, :slug)");
        $ins->execute([':n' => $name, ':slug' => $this->uniqueCatSlug($this->slugify($name))]);
        return (int)$this->db->lastInsertId();
    }

    private function resolveType(string $name): int {
        $s = $this->db->prepare("SELECT id FROM post_types WHERE name=:n LIMIT 1");
        $s->execute([':n' => $name]);
        $id = $s->fetchColumn();
        if ($id) return (int)$id;

        // crear si no existe por algún motivo
        $ins = $this->db->prepare("INSERT INTO post_types (name) VALUES (:n)");
        $ins->execute([':n' => $name]);
        return (int)$this->db->lastInsertId();
    }

    private function slugify(string $text): string {
        $text = iconv('UTF-8','ASCII//TRANSLIT//IGNORE', $text);
        $text = preg_replace('~[^\\pL\\d]+~u', '-', $text);
        $text = trim($text, '-');
        $text = strtolower($text);
        $text = preg_replace('~[^-a-z0-9]+~', '', $text);
        return $text ?: 'post';
    }

    private function uniqueSlug(string $slug): string {
        $base = $slug; $i = 0;
        do {
            $check = $i ? "$base-$i" : $base;
            $s = $this->db->prepare("SELECT 1 FROM posts WHERE slug=:s LIMIT 1");
            $s->execute([':s' => $check]);
            $exists = (bool)$s->fetchColumn();
            if (!$exists) return $check;
            $i++;
        } while(true);
    }

    private function uniqueCatSlug(string $slug): string {
        $base = $slug; $i = 0;
        do {
            $check = $i ? "$base-$i" : $base;
            $s = $this->db->prepare("SELECT 1 FROM categories WHERE slug=:s LIMIT 1");
            $s->execute([':s' => $check]);
            $exists = (bool)$s->fetchColumn();
            if (!$exists) return $check;
            $i++;
        } while(true);
    }
}
