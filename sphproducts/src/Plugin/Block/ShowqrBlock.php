<?php

namespace Drupal\sphproducts\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

/**
 * Provides a 'ShowqrBlock' block.
 *
 * @Block(
 *  id = "showqr_block",
 *  admin_label = @Translation("Show QR block"),
 * )
 */
class ShowqrBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $node = \Drupal::routeMatch()->getParameter('node');
    if ($node instanceof \Drupal\node\NodeInterface) {
      $nid = $node->id();
      if($node->hasField('field_purchase_link') && !$node->get('field_purchase_link')->isEmpty()){
        $url = $node->get('field_purchase_link')->getString();
      }
    }
    $path = getcwd()."/sites/default/files/";
    $filename = "qrcode-".$nid.".png";
    $renderer = new ImageRenderer(
        new RendererStyle(400),
        new ImagickImageBackEnd()
    );
    $writer = new Writer($renderer);
    $writer->writeFile($url, $path.$filename);

    return array(
      '#type' => 'markup',
      '#cache' => [
        'max-age' => 0,
      ],
      '#markup' => '<img src="/sites/default/files/'.$filename.'">',
		);
  }
  /**
   * {@inheritdoc}
   */
  /* Exclude this block from Caching */
  public function getCacheMaxAge() {
    return 0;
 }
}
