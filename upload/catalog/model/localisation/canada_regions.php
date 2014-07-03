<?php
class ModelLocalisationCanadaRegions extends Model {
    
    public function getCanadaRegions() {
        $canada_regions = $this->cache->get('canada_regions');
        
        if (!$canada_regions) {
            $query = $this->db->query("SELECT * FROM canada_regions WHERE 1 ORDER BY name ASC");
    
            $canada_regions = $query->rows;
        
            $this->cache->set('canada_regions', $canada_regions);
        }

        return $canada_regions;
    }
}