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
    public class Photo
    {
        [Key]
        [DatabaseGeneratedAttribute(DatabaseGeneratedOption.Identity)]
        public int PhotoId { get; set; }
        public int RoomId { get; set; }
        public byte[] photo { get; set; }
        public string MimeType { get; set; }

        public ICollection<PhotoComment> Comments { get; set; }
 
        public Photo()
        {
            Comments = new HashSet<PhotoComment>();
        }
    }
}