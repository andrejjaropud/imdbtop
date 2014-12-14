<?php
/**
 * Class IMDB_HTML
 * Get data from Top chart 250(www.imdb.com) and return array which  contains first 20 movies from this Top
 * User: A.Iaropud
 * Date: 10.12.14
 * Time: 16:26
 */

class IMDB_HTML extends CApplicationComponent
{
	private $baseurl = 'http://www.imdb.com/chart/top';
	private $tops = array();

	/**
	 * Get page via Curl , parse page and return array with first 20 movies
	 */
	public function getTopChart()
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->baseurl); //Top chart
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);
		curl_close($ch);
		$imdb_webpage = $output;

		if (preg_match('/<tbody class="lister-list">(.*?)<\/tbody>/s', $imdb_webpage, $hit))
		{
			if (preg_match_all('/<tr class="(odd|even)">(.*?)<\/tr>/s',$hit[0],$top))
			{
				$arr_top = $top[0];
				for($i=0;$i<20;$i++)
				{
					$this->tops[] = $arr_top[$i];
				}
			}
		}
	}

	/**
	 * Return data
	 *
	 * @return array
	 */
	public function getData()
	{
		$this->getTopChart();
		$arr_ret = array();
		$amount = count($this->tops);
		for($i=0;$i<$amount;$i++)
		{
			$arr_ret[$i]['tconst'] = $this->getTconst($this->tops[$i]);
			$arr_ret[$i]['title'] = $this->getTitle($this->tops[$i]);
			$arr_ret[$i]['year'] = $this->getYear($this->tops[$i]);
			$arr_ret[$i]['rating'] = $this->getRating($this->tops[$i]);
			$arr_ret[$i]['num_votes'] = $this->getNumVotes($this->tops[$i]);
		}

		return $arr_ret;
	}

	/**
	 * Get tconst of movie
	 *
	 * @param $el
	 *
	 * @return bool|string
	 */
	private function getTconst($el)
	{

		if (preg_match('/<td class="posterColumn"><a href="\/title\/(.*?)\//s', $el, $tc))
		{
			return trim($tc[1]);
		}
		else
		{
			return false;
		}
	}

	/**
	 * Get movie title
	 *
	 * @param $el
	 *
	 * @return bool|string
	 */
	private function getTitle($el)
	{

		if (preg_match('/<td class="titleColumn">(.*?)<\/a>/s', $el, $tt))
		{
			$pos = strrpos($tt[1],">")+1;
			return trim(substr($tt[1],$pos));
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retunv movie year of release
	 *
	 * @param $el
	 *
	 * @return bool|string
	 */
	private function getYear($el)
	{

		if (preg_match('/class="secondaryInfo">\((.*?)\)/s', $el, $tt))
		{

			return trim($tt[1]);
		}
		else
		{
			return false;
		}
	}

	/**
	 * Return current rating
	 *
	 * @param $el
	 *
	 * @return bool|string
	 */
	private function getRating($el)
	{

		if (preg_match('/<td class="ratingColumn imdbRating">(.*?)<\/strong>/s', $el, $tt))
		{

			$pos = strrpos($tt[1],">")+1;
			return trim(substr($tt[1],$pos));
		}
		else
		{
			return false;
		}
	}

	/**
	 * Return number of votes for movie
	 *
	 * @param $el
	 *
	 * @return bool|string
	 */
	private function getNumVotes($el)
	{

		if (preg_match('/<td class="ratingColumn imdbRating">(.*?)title/s', $el, $tt))
		{
			$pos = strrpos($tt[1],"=")+2;
			$num_votes = trim(substr($tt[1],$pos));
			return substr($num_votes,0,strlen($num_votes)-1);
		}
		else
		{
			return false;
		}
	}
}