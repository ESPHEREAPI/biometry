<?php

namespace Application\Core;

class MsbtExcel
{
	protected $titresColones;
	protected $datas;
	protected $properties;
	protected $objPHPExcel;
	protected $nomFichier;
	protected $numPremiereLigne;
	protected $numPremiereColone;
	
	
	/**
	 * @param array $properties
	 * @param array $titresColones
	 * @param array $datas
	 * @param string $nomFichier
	 * @param int $numPremiereLigne
	 */
	public function __construct(array $properties, array $titresColones, array $datas, $nomFichier, $numPremiereLigne=1, $numPremiereColone=0)
	{
		// Style des cellules
		$style = array(
				'font'      => array(
					'name'  => 'Verdana',
				),
				'alignment' => array(
					'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
					'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER,
				)
		);
		
		// Initialisation de l'objet PHPExcel
		$this->setObjPHPExcel(new \PHPExcel());
		
		// Mise en forme des cellules
		$this->objPHPExcel->getActiveSheet()
						  ->getDefaultRowDimension()
						  ->setRowHeight(25);
		$this->objPHPExcel->getActiveSheet()->getDefaultStyle()->applyFromArray($style);
		
		
		// Initialisation des parametres et des donnes du fichier
		$this->setNomFichier($nomFichier);
		$this->setProperties($properties);
		$this->setNumPremiereLigne($numPremiereLigne);
		$this->setNumPremiereColone($numPremiereColone);
		$this->setTitresColones($titresColones);
		$this->setDatas($datas);
	}
	
	
	public function getNumPremiereColone()
	{
		return $this->numPremiereColone;
	}
	
	protected function setNumPremiereColone($numPremiereColone)
	{
		$this->numPremiereColone = $numPremiereColone;
	
		return $this;
	}
	
	public function getNumPremiereLigne()
	{
		return $this->numPremiereLigne;
	}
	
	protected function setNumPremiereLigne($numPremiereLigne)
	{
		$this->numPremiereLigne = $numPremiereLigne;
	
		return $this;
	}
	
	public function getTitresColones()
	{
		return $this->titresColones;
	}
	
	protected function setTitresColones($titresColones)
	{
		$this->titresColones = $titresColones;
		
		$indiceColone = $this->numPremiereColone;
		foreach($this->titresColones as $uneColone)
		{
			$this->objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($indiceColone, $this->numPremiereLigne, $uneColone["titre"]);
			
			// Pour mettre l'indentation
			// $this->objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setIndent(1);
			
			if(isset($uneColone["largeur"]) && is_numeric($uneColone["largeur"]))
			{
				$stringColone = \PHPExcel_Cell::stringFromColumnIndex($indiceColone);
				$this->objPHPExcel->getActiveSheet()->getColumnDimension($stringColone)->setWidth($uneColone["largeur"]);
			}
			
			$indiceColone++;
		}
		
		
		if(count($this->titresColones) > 0)
		{
			$styleTitre = array(
						        'font'      => array(
									'bold' => true,
						        	'color' => array('rgb' => 'ffffff'),
							        'size'  => 10,
							        'name'  => 'Verdana',
								),
					            'alignment' => array(
					                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
					            ),
// 					            'borders' => array(
// 					                'top'     => array(
// 					                    'style' => \PHPExcel_Style_Border::BORDER_THIN
// 					                )
// 					            ),
								'fill' => array(
									'type' => \PHPExcel_Style_Fill::FILL_SOLID,
									'color' => array('rgb' => '5b9bd5')
								)
// 					            'fill' => array(
// 					                'type'       => \PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
// 					                'rotation'   => 90,
// 					                'startcolor' => array(
// 					                    'argb' => 'FFA0A0A0'
// 					                ),
// 					                'endcolor'   => array(
// 					                    'argb' => 'FFFFFFFF'
// 					                )
// 					            )
					        );
			
			
			
			$stringPremiereColone = \PHPExcel_Cell::stringFromColumnIndex($this->numPremiereColone);
			$stringDerniereColone = \PHPExcel_Cell::stringFromColumnIndex($this->numPremiereColone + count($this->titresColones) -1);
			
			
			$this->objPHPExcel->getActiveSheet()
				->getStyle($stringPremiereColone.$this->numPremiereLigne.":".$stringDerniereColone.$this->numPremiereLigne)
				->applyFromArray($styleTitre);
		}
	
		return $this;
	}
	
	public function getDatas()
	{
		return $this->datas;
	}
	
	protected function setDatas($datas)
	{
		$this->datas = $datas;
		
		$stringPremiereColone = \PHPExcel_Cell::stringFromColumnIndex($this->numPremiereColone);
		$stringDerniereColone = \PHPExcel_Cell::stringFromColumnIndex($this->numPremiereColone + count($this->titresColones) -1);
		
		
		$row = $this->numPremiereLigne; // 1-based index
		if(count($this->titresColones) > 0)
		{
			$row++;
		}

		foreach ($this->datas as $datasLigne)
		{
			$varRetour = $this->constructLine($datasLigne, $row, $this->numPremiereColone);

			if($row%2 == 0)
			{
				$styleHere = array(
								'font'      =>  array(
									'name'  => 'Verdana',
								),
								'fill'  => array(
									'type' => \PHPExcel_Style_Fill::FILL_SOLID,
									'color' => array('rgb' => 'deeaf6')
								),
							);
				
				$this->objPHPExcel->getActiveSheet()
						->getStyle($stringPremiereColone.$row.":".$stringDerniereColone.$row)
						->applyFromArray($styleHere);
			}

			$row += $varRetour;
		}

		return $this;
	}
	
	public function getProperties()
	{
		return $this->properties;
	}
	
	protected function setProperties($properties)
	{
		$this->properties = $properties;
		
		foreach ($this->properties as $propertieName => $propertieValue)
		{
			$method = "set".ucfirst($propertieName);
			$this->objPHPExcel->getProperties()->$method($propertieValue);
		}
	
		return $this;
	}
	
	public function getObjPHPExcel()
	{
		return $this->objPHPExcel;
	}
	
	protected function setObjPHPExcel($objPHPExcel)
	{
		$this->objPHPExcel = $objPHPExcel;
	
		return $this;
	}
	
	public function getNomFichier()
	{
		return $this->nomFichier;
	}
	
	protected function setNomFichier($nomFichier)
	{
		$this->nomFichier = $nomFichier;
	
		return $this;
	}
	
	public function constructLine($datasLigne, $indiceLigne, $indiceColone=0)
	{
		$incrementLigne = 0;
		$indiceColoneDonnees = 0;
		
		while(!isset($datasLigne["titre"]) && isset($datasLigne[$indiceColoneDonnees]) && (is_string($datasLigne[$indiceColoneDonnees]) || is_numeric($datasLigne[$indiceColoneDonnees])))
		{
			$this->objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($indiceColone, $indiceLigne, $datasLigne[$indiceColoneDonnees]);
			$indiceColone++;
			$indiceColoneDonnees++;
		}
		
		if(isset($datasLigne["titre"]) && isset($datasLigne['donnees']))
		{
			$this->objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($indiceColone, $indiceLigne, $datasLigne['titre']);
			
			$indiceColoneInit = $indiceColone;
			$indiceColone++;
			
			if(is_array($datasLigne['donnees']) && count($datasLigne['donnees']) > 0)
			{
				foreach ($datasLigne['donnees'] as $donneesLigneFille)
				{
					$incrementLigne += $this->constructLine($donneesLigneFille, $indiceLigne+$incrementLigne, $indiceColone);
				}
				
				// Merge columns
				$stringColone = \PHPExcel_Cell::stringFromColumnIndex($indiceColoneInit);
				$this->objPHPExcel->getActiveSheet()->mergeCells($stringColone.$indiceLigne.':'.$stringColone.($indiceLigne+$incrementLigne-1));
				
				
				
				
				$style = array(
						'alignment' => array(
							'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
							'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER,
						)
				);
				
				// $this->objPHPExcel->getActiveSheet()->getStyle($stringColone.$indiceLigne.':'.$stringColone.($indiceLigne+$incrementLigne-1))->applyFromArray($style);
			}
		}
		
		if($incrementLigne == 0)
			$incrementLigne++;
	
		return $incrementLigne;
	}
	
	public function download()
	{
//		/********************* Debut pour Excel 2007 ***************************/
// 		// Redirect output to a client’s web browser (Excel2007)
// 		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
// 		header('Content-Disposition: attachment;filename="'.$this->getNomFichier().'.xlsx"');
// 		header('Cache-Control: max-age=0');
// 		// If you're serving to IE 9, then the following may be needed
// 		header('Cache-Control: max-age=1');
		
// 		// If you're serving to IE over SSL, then the following may be needed
// 		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
// 		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
// 		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
// 		header ('Pragma: public'); // HTTP/1.0
		
// 		$objWriter = \PHPExcel_IOFactory::createWriter($this->getObjPHPExcel(), 'Excel2007');
		
// 		ob_end_clean();
// 		$objWriter->save('php://output');
// 		exit;
//		/********************* Fin pour Excel 2007 ***************************/

		
		
// 		$cacheMethod = \PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
// 		$cacheSettings = array( ' memoryCacheSize ' => '2048M');
// 		\PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
		
		
		ini_set('memory_limit', '2048M');
		
		
		
		/********************* Debut pour Excel 2003 ***************************/
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$this->getNomFichier().'.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
		
		$objWriter = \PHPExcel_IOFactory::createWriter($this->getObjPHPExcel(), 'Excel5');
		
		ob_end_clean();
		$objWriter->save('php://output');
		exit;
		/********************* Fin pour excel 2003 *************************/
	}
	
	public function save()
	{
	
	}
}