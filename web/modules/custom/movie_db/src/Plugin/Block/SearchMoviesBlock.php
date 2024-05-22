<?php
/**
 * @file
 *
 */
namespace Drupal\movie_db\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Provides a block with Movie trends of the day
 */
#[Block(
  id: "search_movies_block",
  admin_label: new TranslatableMarkup("Search Movies"),
  category: new TranslatableMarkup("Search Form")
)]
class SearchMoviesBlock extends BlockBase {

  /**
   *
   */


  public function build() {
    return \Drupal::formBuilder()->getForm('Drupal\movie_db\Form\MovieDBSearchForm');
  }
}
