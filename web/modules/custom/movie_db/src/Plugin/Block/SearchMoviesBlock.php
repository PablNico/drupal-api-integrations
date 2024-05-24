<?php
/**
 * @file
 *
 */
namespace Drupal\movie_db\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a block with Movie trends of the day
 */
#[Block(
  id: "search_movies_block",
  admin_label: new TranslatableMarkup("Search Movies"),
  category: new TranslatableMarkup("Search Form")
)]
class SearchMoviesBlock extends BlockBase implements ContainerFactoryPluginInterface{

  /**
   * The form builder.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  public function __construct(array $configuration,
                              $plugin_id,
                              $plugin_definition,
                              FormBuilderInterface $formBuilder) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->formBuilder = $formBuilder;
  }
  public static function create(ContainerInterface $container,
                                array $configuration,
                                $plugin_id,
                                $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('form_builder'),
    );
  }

  public function build() {
    return $this->formBuilder->getForm('Drupal\movie_db\Form\MovieDBSearchForm');
  }
}
