using System;
using System.Collections.Generic;
using System.ComponentModel.DataAnnotations;
using System.ComponentModel.DataAnnotations.Schema;
using System.Data.Entity;
using System.Drawing;
using System.Linq;
using System.Web;

namespace Medical_teleconference.Models
{
    [Table("PhotoComments")]
    public class PhotoComment : DbContext
    {
        [Key]
        [DatabaseGeneratedAttribute(DatabaseGeneratedOption.Identity)]
        public int PhotoCommentId { get; set; }
        public int PhotoId { get; set; }
        public int UserId { get; set; }
        public string Comment { get; set; }
        public Point PCPoint { get; set; }

    }
}