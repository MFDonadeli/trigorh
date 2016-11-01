<?php
    class Preenchimento_model extends CI_Model
    {
        public function get_conteudo($tabela)
        {
            log_message('debug', 'get_conteudo. Param: ' . $tabela);
            
            $result = $this->db->get($tabela);

            log_message('debug', 'get_conteudo. Quantidade: ' . $result->num_rows());
            
            return $result->result();
        }

        public function busca_curso($formacao, $curso)
        {
            log_message('debug', 'busca_curso. Param1: ' . $formacao . ' Param2: ' . $curso);

            $this->db->where('idformacao', $formacao);
            $this->db->like('curso', $curso, 'both');

            $result = $this->db->get('curso');

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            return $result->result();
        }

        public function busca_local($local)
        {
            log_message('debug', 'busca_local. Param1: ' . $local);

            $this->db->like('local', $local, 'both');
            $this->db->order_by('local');
            $this->db->limit(10);

            $result = $this->db->get('localizacao');

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            return $result->result();
        }

        public function busca_informatica($conhecimento, $profissional)
        {
            log_message('debug', 'busca_idioma. Param1: ' . $conhecimento . ' Param2: ' . $profissional);


            $this->db->select('conhecimento.idconhecimento, conhecimento.conhecimento');
            $this->db->from('conhecimento');
            $this->db->join('(select * from conhecimento_profissional where idprofissional=' . $profissional . ') as cp', 
                'conhecimento.idconhecimento = cp.idconhecimento', 'left');
            $this->db->like('conhecimento.conhecimento', $conhecimento, 'both');
            $this->db->where('idtipo_conhecimento', '1');
            $this->db->where('cp.idconhecimento is null');
            $this->db->order_by('conhecimento.conhecimento');
            
            $result = $this->db->get();

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            return $result->result();
        }

        public function busca_idioma($conhecimento, $profissional)
        {
            log_message('debug', 'busca_idioma. Param1: ' . $conhecimento . ' Param2: ' . $profissional);


            $this->db->select('conhecimento.idconhecimento, conhecimento.conhecimento');
            $this->db->from('conhecimento');
            $this->db->join('(select * from conhecimento_profissional where idprofissional=' . $profissional . ') as cp', 
                'conhecimento.idconhecimento = cp.idconhecimento', 'left');
            $this->db->like('conhecimento.conhecimento', $conhecimento, 'both');
            $this->db->where('idtipo_conhecimento', '2');
            $this->db->where('cp.idconhecimento is null');
            $this->db->order_by('conhecimento.conhecimento');
            
            $result = $this->db->get();

            log_message('debug', 'Last Query: ' . $this->db->last_query());

            return $result->result();
        }
    }
?>