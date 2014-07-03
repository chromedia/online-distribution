<?php
class ModelLocalisationUsStates extends Model {
    
    public function getUsStates() {
        $states_data = $this->cache->get('us_states');
        
        if (!$states_data) {
            $query = $this->db->query("SELECT * FROM us_states WHERE 1 ORDER BY name ASC");
    
            $states_data = $query->rows;
        
            $this->cache->set('us_states', $states_data);
        }

        return $states_data;
    }
}